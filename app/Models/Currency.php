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

    public function history()
    {
        return $this->hasMany(CurrencyHistory::class, 'currency_id');
    }

    /**
     * Сохраняет текущие значения курса валюты в историю
     * @return CurrencyHistory
     */
    public function saveToHistory(): CurrencyHistory
    {
        return $this->history()->create([
            'rate' => $this->rate,
            'relevant_on' => $this->relevant_on,
        ]);
    }
}
