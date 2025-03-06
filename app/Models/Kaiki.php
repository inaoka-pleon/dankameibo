<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $new_user
 * @property string $upd_user
 * @property string $kaiki_kbn
 * @property int $kaiki
 * @property string $kaiki_name
 * @property int $target_flg
 * @property string $from_year_kbn
 * @property int $from_month
 * @property int $from_day
 * @property string $to_year_kbn
 * @property int $to_month
 * @property int $to_day
 * @property int $houyou_month
 * @property int $houyou_day
 * @property int $disp_order
 */
class Kaiki extends Model
{
    use HasFactory;

    protected $fillable = [
        'new_user',
        'upd_user',
        'kaiki_kbn',
        'kaiki',
        'kaiki_name',
        'target_flg',
        'from_year_kbn',
        'from_month',
        'from_day',
        'to_year_kbn',
        'to_month',
        'to_day',
        'houyou_month',
        'houyou_day',
        'disp_order',
    ];

    public function nenkis(): HasMany
    {
        return $this->hasMany(Nenki::class);
    }
}
