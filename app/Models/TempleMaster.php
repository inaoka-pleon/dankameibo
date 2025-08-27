<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TempleMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'mountainname',
        'templename',
        'jyushokuname',
        'postcode',
        'address1',
        'address2',
        'tel',
        'fax',
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

        static::creating(function ($templemaster) {
            if (Auth::guard('web')->check()) {
                $templemaster->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });
            

        static::updating(function ($templemaster) {
            if ($templemaster->isDirty('jiin_id')) {
                $templemaster->jiin_id = $templemaster->getOriginal('jiin_id');
            }
        });
    }
}
