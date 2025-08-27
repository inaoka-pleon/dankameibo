<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PharIo\Manifest\Author;

class Kakocho extends Model
{
    use HasFactory;

    protected $fillable = [
        'kaimyou',
        'zokumyou',
        'zokumyoukana',
        'death_era',
        'death_year',
        'death_month',
        'death_day',
        'ageatdeath',
        'kakocho_memo',
        'chiefmourner_flg',
        'deceased_flg',
        'danka_id',
        'jiin_id',
    ];

    public function jiin()
    {
        return $this->belongsTo(Jiin::class, 'jiin_id');
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

        static::creating(function ($kakocho) {
            if (Auth::guard('web')->check()) {
                $kakocho->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });
            

        static::updating(function ($kakocho) {
            if ($kakocho->isDirty('jiin_id')) {
                $kakocho->jiin_id = $kakocho->getOriginal('jiin_id');
            }
        });
    }
}  