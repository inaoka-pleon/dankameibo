<?php

namespace App\Consts;

use PhpParser\Node\Stmt\Const_;

class RelationshipConsts
{
    //家族続柄の定数
    public const HUSBAND = 1;
    public const WIFE = 2;
    public Const CHILD = 3;
    public const ELDESTSON = 4;
    public const ELDESTSONS_WIFE = 5;
    public Const ELDESTSONS_CHILD = 6;
    public const ELDESTDAUGHTER = 7;
    public const ELDESTDAUGHTERS_HUSBAND = 8;
    public Const ELDESTDAUGHTERS_CHILD = 9;
    public const SECONDSON = 10;
    public const SECONDSONS_WIFE = 11;
    public Const SECONDSONS_CHILD = 12;
    public const SECONDDAUGHTER = 13;
    public const SECONDDAUGHTERS_HUSBAND = 14;
    public Const SECONDDAUGHTERS_CHILD = 15;
    public const THIRDSON = 16;
    public const THIRDSONS_WIFE = 17;
    public Const THIRDSONS_CHILD = 18;
    public const THIRDDAUGHTER = 19;
    public const THIRDDAUGHTERS_HUSBAND = 20;
    public Const THIRDDAUGHTERS_CHILD = 21;
    public const FOURTHSON =22;
    public const FOURTHDAUGHTER = 23;
    public Const FIFTHSON = 24;
    public const FATHER = 25;
    public const STEPFATHER = 26;
    public Const MOTHER = 27;
    public const STEPMOTHER = 28;
    public const SISTER2 = 29;
    public const BROTHER2 = 30;
    public Const SISTER1 = 31;
    public const BROTHER1 = 32;
    public const GRANDMOTHER = 33;
    public const GRANDCHILD = 34;
    public Const GRANDFATHER = 35;
    public const GRANDCHILDS_WIFE = 36;
    public const AUNT2 = 37;
    public const UNCLE1 = 38;
    public Const AUNT1 = 39;
    public const UNCLE2 = 40;
    public Const NIECE = 41;
    public const NEPHEW = 42;
    public const RELATIONSHIP_LIST = [
        '夫' => self::HUSBAND,
        '妻' => self::WIFE,
        '子' => self::CHILD,
        '長男' => self::ELDESTSON,
        '長男の妻' => self::ELDESTSONS_WIFE,
        '長男の子' => self::ELDESTSONS_CHILD,
        '長女' => self::ELDESTDAUGHTER,
        '長女の夫' => self::ELDESTDAUGHTERS_HUSBAND,
        '長女の子' => self::ELDESTDAUGHTERS_CHILD,
        '次男' => self::SECONDSON,
        '次男の妻' => self::SECONDSONS_WIFE,
        '次男の子' => self::SECONDSONS_CHILD,
        '次女' => self::SECONDDAUGHTER,
        '次女の夫' => self::SECONDDAUGHTERS_HUSBAND,
        '次女の子' => self::SECONDDAUGHTERS_CHILD,
        '三男' => self::THIRDSON,
        '三男の妻' => self::THIRDSONS_WIFE,
        '三男の子' => self::THIRDSONS_CHILD,
        '三女' => self::THIRDDAUGHTER,
        '三女の夫' => self::THIRDDAUGHTERS_HUSBAND,
        '三女の子' => self::THIRDDAUGHTERS_CHILD,
        '四男' => self::FOURTHSON,
        '四女' => self::FOURTHDAUGHTER,
        '五男' => self::FIFTHSON,
        '父' => self::FATHER,
        '義父' => self::STEPFATHER,
        '母' => self::MOTHER,
        '義母' => self::STEPMOTHER,
        '妹' => self::SISTER2,
        '弟' => self::BROTHER2,
        '姉' => self::SISTER1,
        '兄' => self::BROTHER1,
        '祖母' => self::GRANDMOTHER,
        '孫' => self::GRANDCHILD,
        '祖父' => self::GRANDFATHER,
        '孫の妻' => self::GRANDCHILDS_WIFE,
        '叔母' => self::AUNT2,
        '伯父' => self::UNCLE1,
        '伯母' => self::AUNT1,
        '叔父' => self::UNCLE2,
        '姪' => self::NIECE,
        '甥' => self::NEPHEW,
    ];
}