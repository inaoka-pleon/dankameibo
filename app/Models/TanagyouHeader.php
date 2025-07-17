<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TanagyouHeader extends Model
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

        static::creating(function ($tanagyouHeader) {
            if (Auth::guard('web')->check()) {
                $tanagyouHeader->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });
            

        static::updating(function ($tanagyouHeader) {
            if ($tanagyouHeader->isDirty('jiin_id')) {
                $tanagyouHeader->jiin_id = $tanagyouHeader->getOriginal('jiin_id');
            }
        });
    }
}
