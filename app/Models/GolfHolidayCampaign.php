<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GolfHolidayCampaign extends Model
{
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
