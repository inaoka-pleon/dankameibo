<?php

namespace App\Consts;

class AmpmConsts
{
    //午前午後の定数
    public const AM = 1;
    public const PM = 2;
    public const AMPM_LIST = [
        '午前' => self::AM,
        '午後' => self::PM,
    ];
}