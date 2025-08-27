<?php
namespace App\Services;

use App\Models\Kaiki;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class KaikiData
{
    /**
     * @throws Exception
     */
    public static function Regist(Request $request, $user_name, $id = null): void
    {
        try {
            if (is_null($id)) {
                $kaiki                  = new Kaiki();
                $kaiki->new_user        = $user_name;
                $kaiki->disp_order      = self::GetNewDispOrder();
            } else {
                $kaiki                  = Kaiki::query()->findOrFail($id);
            }

            $kaiki->upd_user        = $user_name;
            $kaiki->kaiki_kbn       = $request->input("kaiki_kbn");
            if (!empty($request->input("kaiki"))) {
                $kaiki->kaiki           = $request->input("kaiki");
            }
            $kaiki->kaiki_name      = $request->input("kaiki_name");
            $kaiki->target_flg      = $request->input("target_flg");

            if (!empty($request->input("from_year_kbn"))) {
                $kaiki->from_year_kbn   = $request->input("from_year_kbn");
            }
            if (!empty($request->input("from_month"))) {
                $kaiki->from_month      = $request->input("from_month");
            }
            if (!empty($request->input("from_day"))) {
                $kaiki->from_day        = $request->input("from_day");
            }
            if (!empty($request->input("to_year_kbn"))) {
                $kaiki->to_year_kbn     = $request->input("to_year_kbn");
            }
            if (!empty($request->input("to_month"))) {
                $kaiki->to_month        = $request->input("to_month");
            }
            if (!empty($request->input("to_day"))) {
                $kaiki->to_day          = $request->input("to_day");
            }
            if (!empty($request->input("houyou_month"))) {
                $kaiki->houyou_month    = $request->input("houyou_month");
            }
            if (!empty($request->input("houyou_day"))) {
                $kaiki->houyou_day      = $request->input("houyou_day");
            }
            if (Auth::guard('web')->check()) {
                $kaiki->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $kaiki->save();
        } catch (Exception $e) {
            throw new Exception($e);
        }

    }

    /**
     * 登録する表示順を取得する
     */
    private static function GetNewDispOrder(    )
    {
        $num    = Kaiki::query()->max('disp_order');
        if (is_null($num)) {
            $num    = 1;
        } else {
            $num    += 1;
        }
        return $num;
    }

    public static function GetYmd($year_kbn, $month, $day): bool|Carbon|null
    {
        $ymd    = null;
        $today  = CommonUtility::SysToday();
        if (!empty($year_kbn) && !empty($month) && !empty($day)) {
            if ($year_kbn === Config::get('const.YearKbn.PreviousYear')) {
                // 前年
                $year   = $today->addYear(-1)->year;
            } else {
                // 今年
                $year   = $today->year;
            }
            if (self::CheckDate($year, $month, $day)) {
                $ymd   = CommonUtility::CreateYmd($year, $month, $day);
            }
        }
        return $ymd;
    }

    /**
     * 日付の妥当性チャック
     * @param $year
     * @param $month
     * @param $day
     * @return bool
     */
    private static function CheckDate($year, $month, $day): bool
    {
        return checkdate($month, $day, $year);
    }


    public static function HatsubonGetYmd($year_kbn, $year, $month, $day): bool|Carbon|null
    {
        $ymd = null;

        if (!empty($year_kbn) && !empty($year) && !empty($month) && !empty($day)) {
            if ($year_kbn === Config::get('const.YearKbn.PreviousYear')) {
                // 前年
                $year -= 1;
            }
            if (self::CheckDate($year, $month, $day)) {
                $ymd = CommonUtility::CreateYmd($year, $month, $day);
            }
        }

        return $ymd;
    }
}


