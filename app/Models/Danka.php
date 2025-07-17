<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Danka extends Model
{
    use HasFactory;

    protected $fillable = [
        'jiin_id',
    ];

    public function jiin()
    {
        return $this->belongsTo(Jiin::class, 'jiin_id');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class);
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

        static::creating(function ($danka) {
            if (Auth::guard('web')->check()) {
                $danka->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });

        static::updating(function ($danka) {
            if($danka->isDirty('jiin_id')) {

            }
        });
    }
}
