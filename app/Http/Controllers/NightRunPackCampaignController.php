<?php

namespace App\Http\Controllers;

use App\Models\NightRunPack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};
use InterventionImage;
use Mail;

class NightRunPackCampaignController extends Controller
{
    // 11月26日（金）～ 12月26日（日）　
    // 04/13（水）～ 05/15（日）23:59

	protected $_f_name = "";
	protected $_l_name = "";
	protected $_f_read = "";
	protected $_l_read = "";
	protected $_age    = "";
	protected $_sex    = "";
	// protected $_size   = "";
	protected $_zip21  = "";
	protected $_zip22  = "";
	protected $_pref21 = "";
	protected $_addr21 = "";
	protected $_street21 = "";
	protected $_tel    = "";
	protected $_email  = "";
    private $_fileExtension = ['jpg', 'jpeg', 'png'];
	private $_baseFileName  = "";
    private $_resize_maxWidth = '400';

	protected $_errorMSG = [];

	protected $_secretariat = "";

	function __construct()
    {
        $this->_secretariat = config('mail.secretariat');
	}

    /**
     * 申し込みフォームを表示
     */
    public function index()
    {
        return view('night_run_pack.index');
    }

    /**
     * 登録処理
     *
     * @return void
     */
    public function register(Request $request)
    {
        try {
            // バリデーションcheck
            $this->checkValidation($request);

            // クラス変数に格納する
            $this->storeVariable($request);

            // 画像処理
            $this->_imgCheckAndUpload($request->image);

            // 応募内容を登録
            DB::beginTransaction();
            $this->insertApplication();

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail();

            DB::commit();
            return redirect()->action('NightRunPackCampaignController@complete');

        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());

            return redirect()->back()->with('status', $e->getMessage())->withInput();
            exit;
        }
    }

    /**
     * バリデーションcheck
     */
    public function checkValidation($request)
    {
        Log::info('checkValidation');
        $this->_errorMSG = [];

        if (!$request->f_name) $this->_errorMSG[] = "苗字を入力してください";
        if (!$request->l_name) $this->_errorMSG[] = "名前を入力してください";
        if (!$request->f_read){
            $this->_errorMSG[] = "ミョウジを入力してください";
        }else if(!preg_match(config('app.ZENKAKUKANA'), $request->f_read)) {
            $this->_errorMSG[] = "ミョウジは全角カナで入力してください";
        }

        if (!$request->l_read){
            $this->_errorMSG[] = "ナマエを入力してください";
        }else if(!preg_match(config('app.ZENKAKUKANA'), $request->l_read)){
            $this->_errorMSG[] = "ナマエは全角カナで入力してください";
        }

        if(empty($request->age)){
            $this->_errorMSG[] = "ご年齢を入力してください";
        }

        if(empty($request->sex)){
            $this->_errorMSG[] = "性別を選択してください";
        }

        if(strlen($request->zip21) <> 3 || strlen($request->zip22) <> 4  )$this->_errorMSG[] = "郵便番号は3桁-4桁で入力してください";
        if (!$request->pref21) $this->_errorMSG[] = "都道府県を入力してください";
        if (!$request->addr21) $this->_errorMSG[] = "市区町村を入力してください";
        if (!$request->street21) $this->_errorMSG[] = "番地を入力してください";

        if (!preg_match(config('app.DENWABANGOU'), $request->tel)) $this->_errorMSG[] = "電話番号は市外局番から-(ハイフン)を含めて入力してください";

        if (!preg_match(config('app.MAILADDRESS'), $request->email1)){
            $this->_errorMSG[] = "メールアドレスを正しく入力してください";
        }else if($request->email1 <> $request->email2){
            $this->_errorMSG[] = "メールアドレスが確認用と一致していません";
        }
        if(empty($request->image)){
            $this->_errorMSG[] = "レシート画像が添付されていません";
        }

        if($this->_errorMSG){
            $errorMessage = implode("<br>\n" , $this->_errorMSG) ;
            throw new \Exception($errorMessage);
        }
    }

    /**
     * クラス変数に格納する
     */
    public function storeVariable($request)
    {
        Log::info('storeVariable');
        $this->_f_name   = $request->f_name;
        $this->_l_name   = $request->l_name;
        $this->_f_read   = $request->f_read;
        $this->_l_read   = $request->l_read;
        $this->_age      = $request->age;
        $this->_sex      = $request->sex;
        $this->_zip21    = $request->zip21;
        $this->_zip22    = $request->zip22;
        $this->_pref21   = $request->pref21;
        $this->_addr21   = $request->addr21;
        $this->_street21 = $request->street21;
        $this->_tel      = $request->tel;
        $this->_email    = $request->email1;
    }

    /**
     * 画像のバリデーションを確認してアップロードする
     *
     * @param [type] $file
     * @return void
     */
    public function _imgCheckAndUpload($file)
    {
        Log::info('_imgCheckAndUpload');
        if(!$file)throw new \Exception("画像が選択されていません");

        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtension($file);

        // ファイル名の作成 => wyr_ {日時} . {拡張子}
        $this->_baseFileName = sprintf(
            '%s_%s.%s',
            'NRP',
            time(),
            $extension
        );

        // 画像を保存する
        $file->storeAs('public/night_run_pack_img', $this->_baseFileName);

        // リサイズして保存する
        $resizeImg = InterventionImage::make($file)
        ->resize($this->_resize_maxWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->orientate()
        ->save(storage_path('app/public/night_run_pack_img_resize/') . $this->_baseFileName);
    }

    /**
     * 渡されたファイルが登録可能な拡張子か確認するしてOKなら拡張子を返す
     */
    public function checkFileExtension($file)
    {
        Log::info('checkFileExtension');
        // 渡された拡張子を取得
        $extension = $file->extension();
        if(! in_array($extension, $this->_fileExtension)){
            $fileExtension = json_encode($this->_fileExtension);
            throw new \Exception("登録できる画像の拡張子は". $fileExtension ."のみです。");
        }
        return $extension;
    }

    /**
     * 申し込み内容をDBに登録
     */
    public function insertApplication()
    {
        Log::info('insertApplication');
        $fc_tokyo = new NightRunPack;
        $fc_tokyo->f_name = $this->_f_name;
        $fc_tokyo->l_name = $this->_l_name;
        $fc_tokyo->f_read = $this->_f_read;
        $fc_tokyo->l_read = $this->_l_read;
        $fc_tokyo->age    = $this->_age;
        $fc_tokyo->sex    = $this->_sex;
        $fc_tokyo->zip21  = $this->_zip21;
        $fc_tokyo->zip22  = $this->_zip22;
        $fc_tokyo->pref21 = $this->_pref21;
        $fc_tokyo->addr21 = $this->_addr21;
        $fc_tokyo->street21 = $this->_street21;
        // $fc_tokyo->size   = $this->_size;
        $fc_tokyo->tel    = $this->_tel;
        $fc_tokyo->email  = $this->_email;
        $fc_tokyo->img_pass	= $this->_baseFileName;
        $fc_tokyo->save();
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    public function sendThankYouMail()
    {
        Log::info('sendThankYouMail');
        $data = [
            "customerName" => $this->_f_name .$this->_l_name
        ];
        Mail::send('emails.night_run_pack_thankYouMail', $data, function($message){
            $message->to($this->_email)
            ->from('nbrun@fluss.co.jp')
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('ご応募ありがとございました。');
        });
    }

    /**
     * レポートメールを送信
     */
    public function sendReportMail()
    {
        Log::info('sendReportMail');
        $sexName = \App\Consts\InputValidation::SEX_LIST[$this->_sex];
        $data = [
            "name" => $this->_f_name . " " .$this->_l_name,
            "read" => $this->_f_read . " " .$this->_l_read,
            "age" => $this->_age,
            "sex_name" => $sexName,
            "zip"  => $this->_zip21 . "-" .$this->_zip22 ,
            "streetAddress"  => $this->_pref21 . "" .$this->_addr21 . "" .$this->_street21 ,
            "tel"  => $this->_tel,
            "email"  => $this->_email,
            "url"  => url('').'/admin'
        ];
        Mail::send('emails.night_run_pack_reportMail', $data, function($message){
            $message->to("nbrun@fluss.co.jp")
            ->from('nbrun@fluss.co.jp')
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('「NIGHT RUN PACKキャンペーン」に申し込みがありました');
        });
    }


    /**
     * 申し込みフォームを表示
     */
    public function complete(){
        return view('night_run_pack.complete');
    }




}
