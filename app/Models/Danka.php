<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Danka extends Model
{
    use HasFactory;

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class);
    }
}
