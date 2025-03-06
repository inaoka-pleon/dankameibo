<?php
namespace App\Services;

use App\Models\Code;
use Illuminate\Support\Facades\Config;

class CodeData
{
    /**
     * 汎用マスタ名一覧を取得する
     * @param $key
     * @return Collection|array
     */
    public static function GetGeneralMasterNameList($key)
    {
        return Code::query()
            ->where('key1', '=', 'MASTER_NAME')
            ->where('key2', '=', $key)
            ->get();
    }

    /**
     * 汎用マスタ取得
     * @param $customer_id
     * @param $master_no
     * @param $paginate
     * @return LengthAwarePaginator|Builder[]|Collection
     */
    public static function GetGeneralMaster($customer_id, $master_no, $paginate = null)
    {
        // 汎用マスタ識別キーを取得する
        $key    = self::GetGeneralMaterKey($master_no);
        $query  = Code::query()
                    ->where('key1', '=', $key)
                    ->where('key2', '=', $customer_id)
                    ->orderBy('key3');
        if (is_null($paginate)) {
            $result = $query->get();
        } else {
            $result = $query->paginate($paginate);
        }
        return $result;
    }

    public static function GetNextIndexNo($key1, $key2)
    {
        $code   = Code::query()
                    ->where('key1', '=', $key1)
                    ->where('key2', '=', $key2)
                    ->max('key3');
        return $code + 1;
    }

    /**
     * カテゴリー名を取得する
     * @param $category_id
     * @param $customer_id
     * @return string
     */
    public static function GetGoodsCategoryName($category_id, $customer_id): string
    {
        $result = '';
        $code   = Code::query()
                    ->where('key1', '=', 'GOODS_CATEGORY')
                    ->where('key2', '=', $customer_id)
                    ->where('key3', '=', $category_id)
                    ->first();
        if (!is_null($code)) {
            $result = $code->value1;
        }
        return $result;
    }

    /**
     * 汎用マスタ識別キーを取得する
     * @param $no
     * @return string
     */
    public static function GetGeneralMaterKey($no): string
    {
        if ($no === Config::get('literal.GeneralMaster.Area')) {
            $key    = "AREA";
        } elseif ($no === Config::get('literal.GeneralMaster.DankaDivision')) {
            $key    = "DANKADIVISION";
        } elseif ($no === Config::get('literal.GeneralMaster.Position')) {
            $key    = "POSITION";
        } elseif ($no === Config::get('literal.GeneralMaster.Relationship')) {
            $key    = "RELATIONSHIP";
        } elseif ($no === Config::get('literal.GeneralMaster.Occupation')) {
            $key    = "OCCUPATION";
        } elseif ($no === Config::get('literal.GeneralMaster.Mortuarytablet')) {
            $key    = "MORTUARYTABLET";
        } elseif ($no === Config::get('literal.GeneralMaster.Manager')) {
            $key    = "MANAGER";
        } elseif ($no === Config::get('literal.GeneralMaster.TempleOffice')) {
            $key    = "TEMPLEOFFICE";
        } elseif ($no === Config::get('literal.GeneralMaster.Title')) {
            $key    = "TITLE";
        } elseif ($no === Config::get('literal.GeneralMaster.SubTitle')) {
            $key    = "SUBTITLE";
        } elseif ($no === Config::get('literal.GeneralMaster.Teacher')) {
            $key    = "TEACHER";
        } elseif ($no === Config::get('literal.GeneralMaster.Jikaku')) {
            $key    = "JIKAKU";
        } elseif ($no === Config::get('literal.GeneralMaster.Qualification')) {
            $key    = "QUALIFICATION";
        } elseif ($no === Config::get('literal.GeneralMaster.NyuukaiName')) {
            $key    = "NYUUKAINAME";
        } elseif ($no === Config::get('literal.GeneralMaster.DankaKaihi')) {
            $key    = "DANKAKAIHI";
        } elseif ($no === Config::get('literal.GeneralMaster.GozikaiKaihi')) {
            $key    = "GOZIKAIKAIHI";
        } else {
            $key    = "";
        }
        return $key;
    }
}