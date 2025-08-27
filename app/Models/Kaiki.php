<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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

    public function jiin()
    {
        return $this->belongsTo(Jiin::class, 'jiin_id');
    }

    public function nenkis(): HasMany
    {
        return $this->hasMany(Nenki::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('by_jiin', function (Builder $builder) {
            if (Auth::guard('web')->check()) {
                $userJiinId = Auth::guard('web')->user()->jiin_id;

                if ($userJiinId) {
                    $builder->where($builder->getModel()->getTable(). '.jiin_id', $userJiinId);
                }
            }
        });

        static::creating(function ($kaiki) {
            if (Auth::guard('web')->check()) {
                $kaiki->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });

        static::updating(function ($kaiki) {
            if($kaiki->isDirty('jiin_id')) {

            }
        });
    }
}
