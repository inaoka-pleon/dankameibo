<?php
namespace App\Services;

use App\Models\Era;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class EraData
{
    /**
     * 元号登録
     * @param Request $request
     * @param $id
     * @return void
     */
    public static function Regist(Request $request, $id = null): void
    {
        $st_ymd     = Carbon::parse($request['start_ymd']);
        $ed_ymd     = Carbon::parse($request['end_ymd']);

        if (is_null($id)) {
            $era                = new Era();
        } else {
            $era                = Era::query()->find($id);
        }

        $era->name          = $request['name'];
        $era->start_ymd     = $st_ymd;
        $era->end_ymd       = $ed_ymd;
        $era->ad_start      = $st_ymd->year;
        $era->years         = $ed_ymd->year - $st_ymd->year + 1;
        $era->save();
    }
}
