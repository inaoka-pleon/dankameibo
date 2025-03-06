<?php
namespace App\Services;

class TaiyaData
{
    /**
     * 逮夜表の日にちを取得
     */
    public static function getTaiyaTitle($value) {
        switch ($value) {
            case 1:
                return "初七日";
            case 2:
                return "二七日";
            case 3:
                return "三七日";
            case 4:
                return "四七日";
            case 9:
                return "初命日";
            case 5:
                return "五七日";
            case 6:
                return "六七日";
            case 7:
                return "七七日";
            case 8:
            default:
                return "百ヶ日";
        }
    }
    /**
     * 逮夜表の日付を計算
     */
    public static function calculateTaiyaDate($deathAnniversary, $value)
    {
        switch ($value) {
            case 1:
                $date = date('Y-m-d', strtotime($deathAnniversary . ' + 5 days'));
                break;
            case 9:
                $date = date('Y-m-d', strtotime($deathAnniversary . ' + 1 month'));
                break;
            case 8:
                $date = date('Y-m-d', strtotime($deathAnniversary . ' + 99 days'));
                break;
            default:
                $date = date('Y-m-d', strtotime($deathAnniversary . ' + ' . ($value * 7 - 2) . ' days'));
                break;
        }
        return $date;
    }
}