<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class why_you_run extends Model
{

    /**
     * 本日来店時に登録した顧客を取得する
     * @param [type] $shop_id
     * @return void
     */
    public static function register($request, $fileName)
    {
        $result = self::insert([
            [
                'coupon_code' => '1' ,
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
                'img_pass'    => $fileName,
            ],
        ]);
        if (!$result){
            return $result;
        }

        $id = DB::getPdo()->lastInsertId();
        $coupon_code = 'WYR' . str_pad($id, 5, 0, STR_PAD_LEFT);

        $record = self::find($id);
        $record->coupon_code = $coupon_code;
        $record->save();
        return true;
    }

    /**
     *
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
