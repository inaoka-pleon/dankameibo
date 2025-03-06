<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    public function dankas() : BelongsTo
    {
        return $this->belongsTo(Danka::class, 'id', 'danka_id');
    }
}
