<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyYouRunQuality extends Model
{
    /**
     * 本日来店時に登録した顧客を取得する
     * @param [type] $shop_id
     * @return void
     */
    public static function register($request)
    {
        $result = self::insert([
            [
                'f_name'      => $request->f_name,
                'l_name'      => $request->l_name,
                'f_read'      => $request->f_read,
                'l_read'      => $request->l_read,
                'tel'         => $request->tel,
                'email'       => $request->email1,
                'zip21'       => $request->zip21,
                'zip22'       => $request->zip22,
                'pref21'      => $request->pref21,
                'addr21'      => $request->addr21,
                'strt21'      => $request->strt21,
            ],
        ]);
        if (!$result){
            return $result;
        }
        return true;
    }

    /**
     *
     * @return void
     */
    public static function getApplyList(){

        $result = self::select('*')
        ->where('delete_flag', 0)
        ->orderBy('created_at', 'desc')
        ->get();

        return $result;
    }
}
