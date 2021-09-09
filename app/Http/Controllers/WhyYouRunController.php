<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhyYouRunController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('why_you_run.index');
    }
}
