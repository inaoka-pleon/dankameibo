<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nenki extends Model
{
    use HasFactory;

    protected $fillable = [
        'new_user',
        'upd_user',
        'kakocho_id',
        'kaiki_id',
        'houyou_date',
    ];

    protected array $dates    = [
        'houyou_date',
    ];

    public function kaiki(): belongsTo
    {
        return $this->belongsTo(Kaiki::class);
    }

    public function kakocho(): belongsTo
    {
        return $this->belongsTo(Kakocho::class);
    }

}
