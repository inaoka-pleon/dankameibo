<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AkihiganHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'era',
        'year',
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

        static::creating(function ($akihiganHeader) {
            if (Auth::guard('web')->check()) {
                $akihiganHeader->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });
            

        static::updating(function ($akihiganHeader) {
            if ($akihiganHeader->isDirty('jiin_id')) {
                $akihiganHeader->jiin_id = $akihiganHeader->getOriginal('jiin_id');
            }
        });
    }
}
