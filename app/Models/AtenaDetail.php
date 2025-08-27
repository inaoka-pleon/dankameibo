<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AtenaDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'namekana',
        'keishou',
        'postcode',
        'address1',
        'address2',
        'postcard',
        'atena_header_id',
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

        static::creating(function ($atenaDetail) {
            if (Auth::guard('web')->check()) {
                $atenaDetail->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });
            

        static::updating(function ($atenaDetail) {
            if ($atenaDetail->isDirty('jiin_id')) {
                $atenaDetail->jiin_id = $atenaDetail->getOriginal('jiin_id');
            }
        });
    }
}
