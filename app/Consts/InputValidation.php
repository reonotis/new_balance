<?php

namespace App\Consts;

// usersで使う定数
class InputValidation
{
    public const SEX_1 = 1;
    public const SEX_2 = 2;
    public const SEX_3 = 3;
    public const SEX_LIST = [
        self::SEX_1 => '男性',
        self::SEX_2 => '女性',
        self::SEX_3 => 'その他',
    ];
}