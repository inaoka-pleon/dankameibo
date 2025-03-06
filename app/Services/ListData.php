<?php
namespace App\Services;

use App\Consts\ManagerConsts;
use Illuminate\Support\Facades\DB;

class ListData
{
    public static function GetAreaListKey($cond_arealist)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'AREA')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_arealist['area'])) {
            $query->where('value1', '=', $cond_arealist['area']);
        }
        return $query->get();
    }

    public static function GetTanagyouHeaderKey($cond_tanagyou_header)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'MANAGER')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_tanagyou_header['manager'])) {
            $query->where('value1', '=', $cond_tanagyou_header['manager']);
        }
        return $query->get();
    }

    public static function GetHaruhiganHeaderKey($cond_haruhigan_header)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'MANAGER')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_haruhigan_header['manager'])) {
            $query->where('value1', '=', $cond_haruhigan_header['manager']);
        }
        return $query->get();
    }

    public static function GetAkihiganHeaderKey($cond_akihigan_header)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'MANAGER')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_akihigan_header['manager'])) {
            $query->where('value1', '=', $cond_akihigan_header['manager']);
        }
        return $query->get();
    }

    public static function GetGozikaiListKey($cond_gozikailist)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'AREA')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_gozikailist['area'])) {
            $query->where('value1', '=', $cond_gozikailist['area']);
        }
        return $query->get();
    }

    public static function GetGozikaikaihiListKey($cond_gozikaikaihi_list)
    {
        $query = DB::table('eras')
                    ->select('id', 
                             'name as era');
        if(!empty($cond_gozikaikaihi_list['era'])) {
            $query->where('name', '=', $cond_gozikaikaihi_list['era']);
        }
        return $query->get();
    }

    public static function GetTempleListKey($cond_templelist)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'TEMPLEOFFICE')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_templelist['templeoffice'])) {
            $query->where('value1', '=', $cond_templelist['templeoffice']);
        }
        if(!empty($cond_temple['templename'])) {
            $query->where('templename', 'like', '%'.$cond_templelist['templename'].'%');
        }
        return $query->get();
    }

    public static function GetKaikireiboListKey($cond_kaikireibo)
    {
        $query  = DB::table('codes')
                    ->where('key1', '=', 'AREA')
                    ->orderBy('key3')
                    ->select('value1');
        if(!empty($cond_kaikireibo['area'])) {
            $query->where('value1', '=', $cond_kaikireibo['area']);
        }
        return $query->get();
    }
}