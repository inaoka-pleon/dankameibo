<?php

use App\Facades\NenkiData;
use App\Services\CommonUtility;
use App\Services\GenericData;
use Carbon\Carbon;

/**
 * 回忌区分名を取得する
 * @param $kbn
 * @return string
 */
function disp_kaiki_kbn($kbn): string
{
    $result = '';
    $kbns   = GenericData::GetGeneric('KAIKI', 'KBN', $kbn);
    foreach ($kbns as $kbn) {
        $result = $kbn->value1;
        break;
    }
    return $result;
}

/**
 * 西暦から和暦に変換して命日を表示する
 * @param $target_date
 * @return string
 */
function AD_to_JA_calender_conv($target_date): string
{
    if (!is_null($target_date)) {
        $result = CommonUtility::ADtoJACalendarConv($target_date);
        $ret    = $result['era_name'] . $result['era_year'] . '年' . $result['month'] . '月' . $result['day'] . '日亡';
    } else {
        $ret    = '';
    }
    return $ret;
}

/**
 * 西暦から和暦に変換して表示する
 * @param $target_date
 * @return string
 */
function AD_to_JA_conv_calender($target_date): string
{
    if (!is_null($target_date)) {
        $result = CommonUtility::ADtoJACalendarConv($target_date);
        $ret    = $result['era_name'] . $result['era_year'] . '年' . $result['month'] . '月' . $result['day'] . '日';
    } else {
        $ret    = '';
    }
    return $ret;
}

/**
 * 表示する・しないを表示する
 * @param $flg
 * @return string
 */
function disp_on_off_name($flg): string
{
    if ($flg === 1) {
        $result = '表示する';
    } else {
        $result = '表示しない';
    }
    return $result;
}

/**
 * 年忌表示
 * @param $kakocho_id
 * @return string
 */
function disp_nenki_next($kakocho_id): string
 {
    $result         = NenkiData::GetNenkiNext($kakocho_id);
    if (!is_null($result)) {
        $kaiki          = empty($result->kaiki_name) ? '' : $result->kaiki_name;
        $target_date    = CommonUtility::ADtoJACalendarConv($result->houyou_date);
        $houyou_date    = $target_date['era_name'] . $target_date['era_year'] . '年' . $target_date['month'] . '月' . $target_date['day'] . '日';
        $ret            = $kaiki . '(' . $houyou_date . ')';
    } else {
        $ret            = '';
    }
    return $ret;
}

/**
 * 和暦から西暦に変換 月日は12月31日で設定
 */

function JA_to_AD_conv($era_id, $year)
{
    $result = CommonUtility::JAtoADCalendarYearConv($era_id, $year);
    if ($result) {
        $date = $result . '-12-31';
        return $date;
    }
    return null;
}

/**
 * 西暦から和暦に変換
 */

function AD_to_JA_conv($target_date)
{
    if (!is_null($target_date)) {
        $result = CommonUtility::ADtoJACalendarConv($target_date);
    } else {
        $result = '';
    }
    return $result;
}

/**
 * 数字から漢数字に変換
 */
 function parse_number($number)
 {
    $kanjiNumbers = [
        '0' => '〇', '０' => '〇',
        '1' => '一', '１' => '一',
        '2' => '二', '２' => '二',
        '3' => '三', '３' => '三',
        '4' => '四', '４' => '四',
        '5' => '五', '５' => '五',
        '6' => '六', '６' => '六',
        '7' => '七', '７' => '七',
        '8' => '八', '８' => '八',
        '9' => '九', '９' => '九'
    ];
    $kanji = '';
    foreach (mb_str_split($number) as $digit) {
        if (isset($kanjiNumbers[$digit])) {
            $kanji .= $kanjiNumbers[$digit];
        } else {
            $kanji .= $digit;
        }
    }
    return $kanji;
 }
