<?php

namespace App\Http\Controllers;

use App\Models\GolfHolidayCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use InterventionImage;
use Mail;

class GolfHolidayCampaignController extends Controller
{
    //11月26日（金）～ 12月26日（日）　

	protected $_f_name = "";
	protected $_l_name = "";
	protected $_f_read = "";
	protected $_l_read = "";
	// protected $_size   = "";
	protected $_zip21  = "";
	protected $_zip22  = "";
	protected $_pref21 = "";
	protected $_addr21 = "";
	protected $_strt21 = "";
	protected $_tel    = "";
	protected $_email  = "";
    private $_fileExtension = ['jpg', 'jpeg', 'png'];
	private $_baseFileName  = "";
    private $_resize_maxWidth = '400';

	protected $_errorMSG = [];

	protected $_secretariat = "";

	function __construct()	{
        $this->_secretariat = config('mail.secretariat');
	}

    /**
     * 申し込みフォームを表示
     */
    public function index(){
        return view('golf_holiday_campaign.index');
    }

    /**
     * 登録処理
     *
     * @return void
     */
    public function register(Request $request){
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
            // dd("登録処理完了。メール送信機能作成中");

            // reportメール
            $this->sendReportMail();

            DB::commit();
            return redirect()->action('GolfHolidayCampaignController@complete');

        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();

            return redirect()->back()->with('status', $e->getMessage())->withInput();
            exit;
        }
    }

    /**
     * バリデーションcheck
     */
    public function checkValidation($request){
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

        if(strlen($request->zip21) <> 3 || strlen($request->zip22) <> 4  )$this->_errorMSG[] = "郵便番号は3桁-4桁で入力してください";
        if (!$request->pref21) $this->_errorMSG[] = "都道府県を入力してください";
        if (!$request->addr21) $this->_errorMSG[] = "市区町村を入力してください";
        if (!$request->strt21) $this->_errorMSG[] = "番地を入力してください";

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
            $errorMasege = implode("<br>\n" , $this->_errorMSG) ;
            throw new \Exception($errorMasege);
        }
    }

    /**
     * クラス変数に格納する
     */
    public function storeVariable($request){
        $this->_f_name = $request->f_name;
        $this->_l_name = $request->l_name;
        $this->_f_read = $request->f_read;
        $this->_l_read = $request->l_read;
        $this->_zip21  = $request->zip21;
        $this->_zip22  = $request->zip22;
        $this->_pref21 = $request->pref21;
        $this->_addr21 = $request->addr21;
        $this->_strt21 = $request->strt21;
        $this->_tel    = $request->tel;
        $this->_email  = $request->email1;
    }

    /**
     * 画像のバリデーションを確認してアップロードする
     *
     * @param [type] $file
     * @return void
     */
    public function _imgCheckAndUpload($file){
        if(!$file)throw new \Exception("画像が選択されていません");

        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtension($file);

        // ファイル名の作成 => wyr_ {日時} . {拡張子}
        $this->_baseFileName = sprintf(
            '%s_%s.%s',
            'GHC',
            time(),
            $extension
        );
        // dd($this->_baseFileName);

        // 画像を保存する
        $file->storeAs('public/golf_img', $this->_baseFileName);

        // リサイズして保存する
        $resizeImg = InterventionImage::make($file)
        ->resize($this->_resize_maxWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->orientate()
        ->save(storage_path('app/public/golf_img_resize/') . $this->_baseFileName);

    }

    /**
     * 渡されたファイルが登録可能な拡張子か確認するしてOKなら拡張子を返す
     */
    public function checkFileExtension($file){
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
    public function insertApplication(){
        $fc_tokyo = new GolfHolidayCampaign;
        $fc_tokyo->f_name = $this->_f_name;
        $fc_tokyo->l_name = $this->_l_name;
        $fc_tokyo->f_read = $this->_f_read;
        $fc_tokyo->l_read = $this->_l_read;
        $fc_tokyo->zip21  = $this->_zip21;
        $fc_tokyo->zip22  = $this->_zip22;
        $fc_tokyo->pref21 = $this->_pref21;
        $fc_tokyo->addr21 = $this->_addr21;
        $fc_tokyo->strt21 = $this->_strt21;
        // $fc_tokyo->size   = $this->_size;
        $fc_tokyo->tel    = $this->_tel;
        $fc_tokyo->email  = $this->_email;
        $fc_tokyo->img_pass	= $this->_baseFileName;
        $fc_tokyo->save();
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    public function sendThankYouMail(){
        $data = [
            "customerName" => $this->_f_name .$this->_l_name
        ];
        Mail::send('emails.golf_thankYouMail', $data, function($message){
            $message->to($this->_email)
            ->from('nbgolf@fluss.co.jp')
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('ご応募ありがとございました。');
        });
    }

    /**
     * レポートメールを送信
     */
    public function sendReportMail(){
        $data = [
            "name" => $this->_f_name . " " .$this->_l_name,
            "read" => $this->_f_read . " " .$this->_l_read,
            // "size" => $this->_size,
            "zip"  => $this->_zip21 . "-" .$this->_zip22 ,
            "streetAddress"  => $this->_pref21 . "" .$this->_addr21 . "" .$this->_strt21 ,
            "tel"  => $this->_tel,
            "email"  => $this->_email,
            "url"  => url('').'/admin'
        ];
        Mail::send('emails.golf_reportMail', $data, function($message){
            $message->to("nbgolf@fluss.co.jp")
            ->from('nbgolf@fluss.co.jp')
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('「ニューバランス ゴルフ ホリデーキャンペーン」に申し込みがありました');
        });
    }


    /**
     * 申し込みフォームを表示
     */
    public function complete(){
        return view('golf_holiday_campaign.complete');
    }




}
