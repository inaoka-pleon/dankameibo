<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generic extends Model
{
    use HasFactory;

    protected $fillable = [
        'key1',
        'key2',
        'key3',
        'value1',
        'value2',
        'value3',
    ];
}
