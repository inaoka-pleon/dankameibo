<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountno1',
        'accountno2',
        'accountno3',
        'name',
        'price',
        'jiin_id',
    ];

    public function jiin()
    {
        return $this->belongsTo(Jiin::class, 'jiin_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('by_jiin', function(Builder $builder) {
            if (Auth::guard('web')->check()) {
                $userJiinId = Auth::guard('web')->user()->jiin_id;

                if($userJiinId) {
                    $builder->where($builder->getModel()->getTable(). '.jiin_id', $userJiinId);
                }
            }
        });
        
        static::creating(function($paymentslip) {
            if(Auth::guard('web')->check()) {
                $paymentslip->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });

        static::updating(function ($paymentslip) {
            if ($paymentslip->isDirty('jiin_id')) {
                $paymentslip->jiin_id = $paymentslip->getOriginal('jiin_id');
            }
        });
    }
}
