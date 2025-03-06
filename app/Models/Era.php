<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Era extends Model
{
    use HasFactory;

    // protected $dates = [
    //     'start_ymd', 
    //     'end_ymd',
    // ];

    protected $fillable = [
        'name',
        'years',
        'ad_start',
        'start_ymd',
        'end_ymd',
    ];
}
