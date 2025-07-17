<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AkihiganDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'namekana',
        'postcode',
        'address1',
        'address2',
        'tel',
        'month',
        'day',
        'ampm',
        'hour',
        'minute',
        'manager',
        'danka_id',
        'haruhigan_header_id',
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

        static::creating(function ($akihiganDetail) {
            if (Auth::guard('web')->check()) {
                $loggedInUser = Auth::guard('web')->user();
                if (!empty($loggedInUser->jiin_id)) {
                    $akihiganDetail->jiin_id = $loggedInUser->jiin_id;
                } else {
                    throw new \Exception('ログインユーザーの寺院IDが設定されていません。');
                }
            } else {
                throw new \Exception('ログインしていません。');
            }
        });
            

        static::updating(function ($akihiganDetail) {
            if ($akihiganDetail->isDirty('jiin_id')) {
                $akihiganDetail->jiin_id= $akihiganDetail->getOriginal('jiin_id');
            }
        });
    }
}
