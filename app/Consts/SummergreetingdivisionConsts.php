<?php

namespace App\Consts;

class SummergreetingdivisionConsts
{
    //暑中見舞区分の定数
    public const SEND = 1;
    public const NOTSEND = 2;
    public const SUMMERGREETINGDIVISION_LIST = [
        '出す' => self::SEND,
        '出さない' => self::NOTSEND,
    ];
}