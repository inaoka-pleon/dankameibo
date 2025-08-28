<?php
namespace App\Services;

use App\Consts\ManagerConsts;
use Illuminate\Support\Facades\DB;

class ListData
{
    public static function GetAreaListKey($cond_arealist, $userJiinId)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'AREA')
                    ->where('jiin_id', '=', $userJiinId)
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_arealist['area'])) {
            $query->where('value1', '=', $cond_arealist['area']);
        }
        return $query->get();
    }

    public static function GetTanagyouHeaderKey($cond_tanagyou_header, $userJiinId)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'MANAGER')
                    ->where('jiin_id', '=', $userJiinId)
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_tanagyou_header['manager'])) {
            $query->where('value1', '=', $cond_tanagyou_header['manager']);
        }
        return $query->get();
    }

    public static function GetHaruhiganHeaderKey($cond_haruhigan_header, $userJiinId)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'MANAGER')
                    ->where('jiin_id', '=', $userJiinId)
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_haruhigan_header['manager'])) {
            $query->where('value1', '=', $cond_haruhigan_header['manager']);
        }
        return $query->get();
    }

    public static function GetAkihiganHeaderKey($cond_akihigan_header, $userJiinId)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'MANAGER')
                    ->where('jiin_id', '=', $userJiinId)
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_akihigan_header['manager'])) {
            $query->where('value1', '=', $cond_akihigan_header['manager']);
        }
        return $query->get();
    }

    public static function GetGozikaiListKey($cond_gozikailist, $userJiinId)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'AREA')
                    ->where('jiin_id', '=', $userJiinId)
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_gozikailist['area'])) {
            $query->where('value1', '=', $cond_gozikailist['area']);
        }
        return $query->get();
    }
}