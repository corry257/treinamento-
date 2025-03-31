<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'csv_id',
        'diet', 
        'pulse',
        'time',
        'kind'
    ];
}
