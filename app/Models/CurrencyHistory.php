<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate', 'relevant_on',
    ];

    protected $dates = [
        'relevant_on',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
