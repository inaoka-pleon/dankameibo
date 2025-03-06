<?php

namespace App\Consts;

class PostcardConsts
{
    //はがき区分の定数
    public const SEND = 1;
    public const NOTSEND = 2;
    public const POSTCARD_LIST = [
        '出す' => self::SEND,
        '出さない' => self::NOTSEND,
    ];
}