<?php

namespace App\Http\Controllers;

use App\Models\why_you_run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InterventionImage;
use Mail;


class WhyYouRunController extends Controller
{

    private $_fileExtntion = ['jpg', 'jpeg', 'png'];
	private $_baseFileName  = "";
    private $_resize_maxWidth = '400';

	protected $_f_name = "";
	protected $_l_name = "";
	protected $_f_read = "";
	protected $_l_read = "";
	protected $_size   = "";
	protected $_zip21  = "";
	protected $_zip22  = "";
	protected $_pref21 = "";
	protected $_addr21 = "";
	protected $_strt21 = "";
	protected $_tel    = "";
	protected $_email  = "";
	protected $_coupon_code  = "";

	protected $_errorMSG = [];

	protected $_secretariat = "";

	function __construct()	{
        $this->_secretariat = config('mail.secretariat');
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('why_you_run.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
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

            DB::beginTransaction();

            // 応募内容を登録
            $response = why_you_run::register($request, $this->_baseFileName);
            if (!$response){
                $this->_errorMSG[] = "申し込みに失敗しました。<br>お手数ですが直接事務局までお問い合わせください";
                $errorMessage = implode("<br>\n" , $this->_errorMSG) ;
                throw new \Exception($errorMessage);
            }

            $this->_coupon_code = $response->coupon_code ;

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail();

            DB::commit();
            return redirect()->action('WhyYouRunController@complete');
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            return redirect()->back()->with('status', $e->getMessage())->withInput();
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

        // if(strlen($request->zip21) <> 3 || strlen($request->zip22) <> 4  )$this->_errorMSG[] = "郵便番号は3桁-4桁で入力してください";
        // if (!$request->pref21) $this->_errorMSG[] = "都道府県を入力してください";
        // if (!$request->addr21) $this->_errorMSG[] = "市区町村を入力してください";
        // if (!$request->strt21) $this->_errorMSG[] = "番地を入力してください";

        // if (!preg_match(config('app.DENWABANGOU'), $request->tel)) $this->_errorMSG[] = "電話番号は市外局番から-(ハイフン)を含めて入力してください";

        if (!preg_match(config('app.MAILADDRESS'), $request->email1)){
            $this->_errorMSG[] = "メールアドレスを正しく入力してください";
        }else if($request->email1 <> $request->email2){
            $this->_errorMSG[] = "メールアドレスが確認用と一致していません";
        }

        if($this->_errorMSG){
            $errorMessage = implode("<br>\n" , $this->_errorMSG) ;
            throw new \Exception($errorMessage);
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
        if(!$file)throw new \Exception("ファイルが指定されていません");

        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtension($file);

        // ファイル名の作成 => wyr_ {日時} . {拡張子}
        $this->_baseFileName = sprintf(
            '%s_%s.%s',
            'wyr',
            time(),
            $extension
        );

        // 画像を保存する
        $file->storeAs('public/why_you_run_receipt_img', $this->_baseFileName);

        // リサイズして保存する
        $resizeImg = InterventionImage::make($file)
        ->resize($this->_resize_maxWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->orientate()
        ->save(storage_path('app/public/why_you_run_receipt_img_resize/') . $this->_baseFileName);

    }

    /**
     * 渡されたファイルが登録可能な拡張子か確認するしてOKなら拡張子を返す
     */
    public function checkFileExtension($file){
        // 渡された拡張子を取得
        $extension =  $file->extension();
        if(! in_array($extension, $this->_fileExtntion)){
            $fileExtntion = json_encode($this->_fileExtntion);
            throw new \Exception("登録できる画像の拡張子は". $fileExtntion ."のみです。");
        }
        return $file->extension();
    }

    /**
     * レポートメールを送信
     */
    public function sendThankYouMail(){
        $data = [
            "name" => $this->_f_name . " " .$this->_l_name,
            "read" => $this->_f_read . " " .$this->_l_read,
            "coupon_code" => $this->_coupon_code,
            "zip"  => $this->_zip21 . "-" .$this->_zip22 ,
            "streetAddress"  => $this->_pref21 . "" .$this->_addr21 . "" .$this->_strt21 ,
            "tel"  => $this->_tel,
            "email"  => $this->_email,
            "url"  => url('').'/admin'
        ];
        Mail::send('emails.why_you_run_thankYouMail', $data, function($message){
            $message->to($this->_email)
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('「10K CHAERGE」イベントで使えるキャンペーンへのお申し込みが完了しました。');
        });
    }

    /**
     * レポートメールを送信
     */
    public function sendReportMail(){
        $data = [
            "name" => $this->_f_name . " " .$this->_l_name,
            "read" => $this->_f_read . " " .$this->_l_read,
            "zip"  => $this->_zip21 . "-" .$this->_zip22 ,
            "streetAddress"  => $this->_pref21 . "" .$this->_addr21 . "" .$this->_strt21 ,
            "tel"  => $this->_tel,
            "email"  => $this->_email,
            "url"  => url('').'/admin'
        ];
        Mail::send('emails.why_you_run_reportMail', $data, function($message){
            $message->to($this->_secretariat)
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('「10K CHAERGE」イベントで使えるキャンペーンへの申し込みがありました');
        });
    }

    /**
     *
     */
    public function complete(){
        return view('why_you_run.complete');
    }

}
