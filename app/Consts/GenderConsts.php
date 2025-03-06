<?php

namespace App\Consts;

class GenderConsts
{
    //性別の定数
    public const MAN = 1;
    public const WOMAN = 2;
    public const GENDER_LIST = [
        '男性' => self::MAN,
        '女性' => self::WOMAN,
    ];
}