<?php
namespace App\Services;

use App\Models\Era;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommonUtility
{
    /**
     * システム日付取得
     * @return Carbon
     */
    public static function SysToday(): Carbon
    {
        return Carbon::today();
    }

    /**
     * システム日時取得
     * @return Carbon
     */
    public static function SysDateTime(): Carbon
    {
        return Carbon::now();
    }

    public static function CreateYmd($year, $month, $day): bool|Carbon|null
    {
        try {
            $result = Carbon::create($year, $month, $day);
        } catch (\Exception $e) {
            $result = null;
        }
        return $result;
    }

    /**
     * 検索条件をセッションから取得する
     * @param Request $request
     * @param $area
     * @param $put_flg
     * @return mixed|null
     */
    public static function GetQueryParameter(Request $request, $name, $put_flg): mixed
    {
        if ($put_flg) {
            $request->session()->put($name, $request->input($name));
        }
        if($request->session()->has($name) === true) {
            // Keyが存在する場合は値を取得する
            $result = $request->session()->get($name);
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * 西暦を和暦に変換する
     * @param $target_date
     * @return array
     */
    public static function ADtoJACalendarConv($target_date): array
    {
        $era    = DB::table('eras')
                    ->where('start_ymd', '<=', $target_date)
                    ->where('end_ymd', '>=', $target_date)
                    ->first();

        $ymd    = Carbon::parse($target_date);
        return [
            'year'      => $ymd->year,
            'month'     => $ymd->month,
            'day'       => $ymd->day,
            'era_id'    => $era->id,
            'era_name'  => $era->name,
            'era_year'  => $ymd->year - $era->ad_start + 1
        ];
    }

    /**
     * 和暦(年)を西暦(年)に変換する
     * @param $era_id
     * @param $era_year
     * @return int
     */
    public static function JAtoADCalendarYearConv($era_id, $era_year): int
    {
        $era    = Era::query()->find($era_id);
        // dd($era);
        if (is_null($era)) {
            $result = $era_year;
        } else {
            $result = $era->ad_start + $era_year - 1;
        }
        return (int)$result;
    }
    /**
     * 和暦(年)を西暦(年)に変換する
     * @param $era_id
     * @param $era_year
     * @return int
     */
    public static function JAtoADCalendarEraNameConv($era_name, $era_year): int
    {
        $era    = Era::query()->where('name', $era_name)->first();
        if (is_null($era)) {
            $result = $era_year;
        } else {
            $result = $era->ad_start + $era_year - 1;
        }
        return (int)$result;
    }


    /**
     * 登録した年月日を西暦から和暦に変換
     */

    public static function parseDate($date)
    {
        $carbonDate = Carbon::parse($date);
        return [
            'year' => $carbonDate->year,
            'month' => $carbonDate->month,
            'day' => $carbonDate->day
        ];
    }

    /**
     * 数字を漢数字に変換する
     */
    public static function parseNumber($number)
    {
        if ($number === null) {
            return '';
        }
        $kanjiNumbers = [
                         '0' => '', '０' => '',
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
        $kanjiDigits = ['', '十', '百', '千'];
        $kanji = '';
        $digits = str_split(strrev($number));
        foreach ($digits as $i => $digit) {
            if ($digit !== '0') {
                if ($i == 1 && $digit == '1') {
                    $kanji = $kanjiDigits[$i] . $kanji;
                } else {
                    $kanji = $kanjiNumbers[$digit] . $kanjiDigits[$i] . $kanji;
                }
            } elseif ($i === 0 || $digits[$i - 1] !== '0') {
                $kanji = $kanjiNumbers[$digit] . $kanji;
            }
        }
        return $kanji;
    }

    /**
     * 日付から曜日を取得して、（曜日）の形で返す
     */
    public static function getDayOfweef($date)
    {
        $dayOfWeek = date('l', strtotime($date));
        $dayOfWeekJapanese = [
            'Sunday' => '日',
            'Monday' => '月',
            'Tuesday' => '火',
            'Wednesday' => '水',
            'Thursday' => '木',
            'Friday' => '金',
            'Saturday' => '土'
        ];
        return '（' . $dayOfWeekJapanese[$dayOfWeek] . '）';
    }
}