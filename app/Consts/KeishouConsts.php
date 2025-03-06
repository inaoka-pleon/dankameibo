<?php

namespace App\Consts;

class KeishouConsts
{
    //宛名敬称の定数
    public const SAMA = 1;
    public const TONO = 2;
    public const ONTYUU = 3;
    public const KEISHOU_LIST = [
        '様' => self::SAMA,
        '殿' => self::TONO,
        '御中' => self::ONTYUU,

    ];
}