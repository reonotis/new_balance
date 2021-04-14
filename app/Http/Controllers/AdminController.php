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
        return view('admin.index');
    }

}
