<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NenkiDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document1',
        'document2',
        'document3',
        'document4',
        'document5',
        'document6',
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
        
        static::creating(function($NenkiDocument) {
            if(Auth::guard('web')->check()) {
                $NenkiDocument->jiin_id = Auth::guard('web')->user()->jiin_id;
            }
        });

        static::updating(function ($NenkiDocument) {
            if ($NenkiDocument->isDirty('jiin_id')) {
                $NenkiDocument->jiin_id = $NenkiDocument->getOriginal('jiin_id');
            }
        });
    }
}
