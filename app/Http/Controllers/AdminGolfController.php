<?php

namespace App\Http\Controllers;

use App\Models\GolfHolidayCampaign;
// use Illuminate\Http\Request;

class AdminGolfController extends Controller
{
    //
    public function index()
    {
        $dataList = GolfHolidayCampaign::getApplyList();
        // dd($dataList);
        return view('admin.golf', compact('dataList'));
    }

}
