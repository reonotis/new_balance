<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\fc_tokyo_application;
use Mail;

// define("ZENKAKUKANA",  "/^[ァ-ヶー]+$/u");
// define("MAILADDRESS",  "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/");
// define("DENWABANGOU",  "/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/");
// define("BIRTHDAY",  "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/");

class FcTokyoApplicationController extends Controller
{
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

	protected $_errorMSG = [];

	protected $_secretariat = "";

	function __construct()	{
        $this->_secretariat = config('mail.secretariat');
	}

    /**
     * 申し込みフォームを表示
     */
    public function index(){
        return view('fc_tokyo.index');
    }

    /**
     * 申し込み内容を登録
     */
    public function aplication(Request $request){
        try {
            // バリデーションcheck
            $this->checkValidation($request);

            // クラス変数に格納する
            $this->storeVariable($request);

            // 応募内容を登録
            $this->insertApplication();

            // thank youメール
            $this->sendThankYouMail();

            // reportメール
            $this->sendReportMail();

            return redirect()->action('FcTokyoApplicationController@complete');
            return view('fc_tokyo.index');

        } catch (\Exception $e) {
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


        // $array_size = array('S', 'M', 'L', 'XL');
        // $result_size = in_array($request->size, $array_size);
        // if(!$result_size) $this->_errorMSG[] = "サイズを正しく選択してください";


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
        // $this->_size   = $request->size;
        $this->_zip21  = $request->zip21;
        $this->_zip22  = $request->zip22;
        $this->_pref21 = $request->pref21;
        $this->_addr21 = $request->addr21;
        $this->_strt21 = $request->strt21;
        $this->_tel    = $request->tel;
        $this->_email  = $request->email1;
    }

    /**
     * 申し込み内容をDBに登録
     */
    public function insertApplication(){
        $fc_tokyo = new fc_tokyo_application;
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
        $fc_tokyo->save();
    }

    /**
     * 申し込み者に自動返信メールを送信
     */
    public function sendThankYouMail(){
        $data = [
            "customerName" => $this->_f_name .$this->_l_name
        ];
        Mail::send('emails.fc_tokyo_thankYouMail', $data, function($message){
            $message->to($this->_email)
            ->from('nb_aruku-tokyo@fluss.co.jp')
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('申し込みを受け付けました。');
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
        Mail::send('emails.fc_tokyo_reportMail', $data, function($message){
            $message->to("nb_aruku-tokyo@fluss.co.jp")
            ->from('nb_aruku-tokyo@fluss.co.jp')
            ->bcc("fujisawareon@yahoo.co.jp")
            ->subject('申し込みがありました');
        });
    }


    /**
     * 申し込みフォームを表示
     */
    public function complete(){
        return view('fc_tokyo.complete');
    }

}