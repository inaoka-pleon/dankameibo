<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AtenaHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
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

        static::creating(function ($atenaHeader) {
            if (Auth::guard('web')->check()) {
                $atenaHeader->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });
            

        static::updating(function ($atenaHeader) {
            if ($atenaHeader->isDirty('jiin_id')) {
                $atenaHeader->jiin_id = $atenaHeader->getOriginal('jiin_id');
            }
        });
    }
}
