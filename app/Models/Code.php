<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'key1',
        'key2',
        'key3',
        'value1',
        'value2',
        'jiin_id',
    ];

    public function jiin()
    {
        return $this->belongsTo(Jiin::class, 'jiin_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('by_jiin_conditional', function (Builder $builder) {
            if (Auth::guard('web')->check()) {
                $userJiinId = Auth::guard('web')->user()->jiin_id;

                // ログインユーザーがjiin_idを持ち、かつ対象のkey1が共通マスタではない場合のみフィルタリング
                if ($userJiinId) {
                    $builder->where(function ($query) use ($userJiinId) {
                        $query->where('codes.jiin_id', $userJiinId)
                              ->orWhereNull('codes.jiin_id');
                    });
                }
            }
        });

        static::creating(function ($code) {
            if ($code->key1 === 'MASTER_NAME') {
                $code->jiin_id = null;
            } else {
                if (Auth::guard('web')->check()) {
                    $loggedInUser = Auth::guard('web')->user();
                    if (!empty($loggedInUser->jiin_id)) {
                        $code->jiin_id = $loggedInUser->jiin_id;
                    } else {
                        // jiin_id が必須なのに取得できない場合のハンドリング
                        throw new \Exception('ログインユーザーの寺院IDが設定されていません。');
                    }
                } else {
                    throw new \Exception('ログインしていません。');
                }
            }
        });

        static::updating(function ($code) {
            // jiin_id が変更されようとした場合、元の値に戻す (保護)
            if ($code->isDirty('jiin_id')) {
                $code->jiin_id = $code->getOriginal('jiin_id');
            }
        });
    }
}
