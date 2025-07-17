<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jiin extends Model
{
    use HasFactory;

    protected $fillable = [
        'jiin_name',
        'memo',
    ];
}
