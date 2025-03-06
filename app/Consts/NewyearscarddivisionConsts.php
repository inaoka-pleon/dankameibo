<?php

namespace App\Consts;

class NewyearscarddivisionConsts
{
    //年賀状区分の定数
    public const SEND = 1;
    public const NOTSEND = 2;
    public const NEWYEARSCARDDIVISION_LIST = [
        '出す' => self::SEND,
        '出さない' => self::NOTSEND,
    ];
}