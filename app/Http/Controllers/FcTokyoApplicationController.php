<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\fc_tokyo_application;
define("ZENKAKUKANA",  "/^[ァ-ヶー]+$/u");

class FcTokyoApplicationController extends Controller
{
	protected $name = "";

	function __construct()	{
		$this->name = "鈴木";
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

            $fc_tokyo = new fc_tokyo_application;
            $fc_tokyo ->f_name = $request->f_name;
            $fc_tokyo ->l_name = $request->l_name;
            $fc_tokyo ->f_read = $request->f_read;
            $fc_tokyo ->l_read = $request->l_read;
            $fc_tokyo ->zip21 = $request->zip21;
            $fc_tokyo ->zip22 = $request->zip22;
            $fc_tokyo ->pref21 = $request->pref21;
            $fc_tokyo ->addr21 = $request->addr21;
            $fc_tokyo ->strt21 = $request->strt21;
            $fc_tokyo ->tel = $request->tel;
            $fc_tokyo ->email = $request->email1;
            $fc_tokyo ->save();
            dd($fc_tokyo);

            // thank youメール

            // reportメール
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
        if (!preg_match(ZENKAKUKANA, $request->f_read)) {
            throw new \Exception('ヨミカタは全角カナで入力してください');
        }
        dd("強制");
    }

}
