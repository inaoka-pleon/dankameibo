<?php
namespace App\Facades;

use App\Models\Follower;
use App\Models\Kaiki;
use App\Models\Nenki;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

/**
 * @see \Illuminate\Cache\CacheManager
 * @see \Illuminate\Cache\Repository
 */
class NenkiData extends Facade
{
    /**
     * 年忌登録
     * @param $kakocho_id
     * @param $user_name
     * @return void
     * @throws Exception
     */
    public static function Regist($kakocho_id, $user_name)
    {
        $ret    = false;
        try {
            $kakocho = Follower::query()->find($kakocho_id);
            if (!is_null($kakocho->death_anniversary)) {
                Nenki::query()->where('kakocho_id', '=', $kakocho_id)->delete();
                $death_anniversary  = $kakocho->death_anniversary;

                $kaikis = Kaiki::query()->where('target_flg', '=', 1)->get();
                foreach ($kaikis as $kaiki) {
                    $nenki                  = new Nenki();
                    $nenki->new_user        = $user_name;
                    $nenki->upd_user        = $user_name;
                    $nenki->kakocho_id      = $kakocho_id;
                    $nenki->kaiki_id        = $kaiki->id;
                    $nenki->houyou_date     = self::GetHouyouData($death_anniversary, $kaiki);
                    $nenki->save();
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            throw new Exception($e);
        }
    }

    /**
     * 次の法要を取得する
     * @param $kakocho_id
     * @return object|null
     */
    public static function GetNenkiNext($kakocho_id): object|null
    {
        $today  = CommonUtility::SysToday();
        $nenki  = DB::table('nenkis')
                    ->join('kaikis', 'nenkis.kaiki_id', '=', 'kaikis.id')
                    ->where('nenkis.kakocho_id', '=', $kakocho_id)
                    ->whereDate('nenkis.houyou_date', '>=', $today)
                    ->select('kaikis.kaiki_name', 'nenkis.houyou_date')
                    ->orderBy('nenkis.houyou_date')
                    ->first();
        return $nenki;
    }

    /**
     * @param $target_date
     * @param $kaiki
     * @return Carbon|null
     * @throws Exception
     */
    private static function GetHouyouData($target_date, $kaiki): Carbon|null
    {
        try {
            $result = null;
            $death_anniversary = Carbon::parse($target_date);
            switch ($kaiki->kaiki_kbn) {
                case '0':   // 年忌
                    if ($kaiki->kaiki == 1) {
                        $result = $death_anniversary->addYear($kaiki->kaiki);
                    } else {
                        $result = $death_anniversary->addYear($kaiki->kaiki-1);
                    }
                    break;
                case '1':   // 百箇日
                    $result = $death_anniversary->addDay(99);
                    break;
                case '2':   // 花初め
                case '3':   // 初盆施食会
                    $ymd    = Carbon::create($death_anniversary->year, $kaiki->to_month, $kaiki->to_day);
                    if ($death_anniversary->gt($ymd)) {
                        $result = Carbon::create($death_anniversary->year, $kaiki->houyou_month, $kaiki->houyou_day)->addYear(1);
                    } else {
                        $result = Carbon::create($death_anniversary->year, $kaiki->houyou_month, $kaiki->houyou_day);
                    }
                    break;
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
