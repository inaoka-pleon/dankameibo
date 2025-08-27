<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AkihiganDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'document1',
        'document2',
        'document3',
        'document4',
        'document5',
        'document6',
        'document7',
        'document8',
        'document9',
        'document10',
        'document11',
        'document12',
        'kakui',
        'templename',
        'address',
        'tel',
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
        
        static::creating(function($AkihiganDocument) {
            if(Auth::guard('web')->check()) {
                $AkihiganDocument->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });

        static::updating(function ($AkihiganDocument) {
            if ($AkihiganDocument->isDirty('jiin_id')) {
                $AkihiganDocument->jiin_id = $AkihiganDocument->getOriginal('jiin_id');
            }
        });
    }
}
