<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\fc_tokyo_application;

class Admin_FC_TOKYO_Controller extends Controller
{
    /**
     * 確認画面を表示
     */
    public function index(){
        $appLists = fc_tokyo_application::get();
        return view('admin.fc_tokyo', compact('appLists'));
    }
}
