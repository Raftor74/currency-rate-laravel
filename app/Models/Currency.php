<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'char_code', 'name', 'rate', 'relevant_on',
    ];

    protected $dates = [
        'relevant_on',
    ];
}
