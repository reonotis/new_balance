<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    /**
     * 確認画面を表示
     */
    public function index(){
        dd(1);
        return view('fc_tokyo.index');
    }

}
