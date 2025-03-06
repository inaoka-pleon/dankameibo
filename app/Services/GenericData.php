<?php
namespace App\Services;

use App\Models\Generic;
use Illuminate\Database\Eloquent\Collection;

class GenericData
{
    /**
     * 汎用データを取得する
     * @param $key1
     * @param $key2
     * @param $key3
     * @return Collection|array
     */
    public static function GetGeneric($key1, $key2 = null, $key3 = null): Collection|array
    {
        $query = Generic::query()->where('key1', '=', $key1);
        if (!is_null($key2)) {
            $query->where('key2', '=', $key2);
        }
        if (!is_null($key3)) {
            $query->where('key3', '=', $key3);
        }
        return $query->select('*')->get();
    }
}