<?php

namespace App\Services\Currency;

use App\Models\Currency;
use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Models\CurrencyRate;

class CurrencyRateStorage
{
    public function storeCurrencyRateCollection(CurrencyRateCollection $collection)
    {
        $existedCharCodes = [];
        $charCodes = $collection->getCurrencyCharCodes();
        $currencies = Currency::query()->whereIn('char_code', $charCodes)->get();

        foreach ($currencies as $currency) {
            $charCode = $currency->char_code;
            $currencyRate = $collection->getByCharCode($charCode);
            $this->storeCurrencyRate($currencyRate, $currency);
            $existedCharCodes[] = $charCode;
        }

        $nonExistedCharCodes = array_diff($charCodes, $existedCharCodes);

        foreach ($nonExistedCharCodes as $charCode) {
            $currencyRate = $collection->getByCharCode($charCode);
            $this->storeCurrencyRate($currencyRate);
        }
    }

    public function storeCurrencyRate(CurrencyRate $currencyRate, ?Currency $currency = null): Currency
    {
        /** @var Currency $_currency */
        $_currency = (!is_null($currency))
            ? $currency
            : Currency::query()->where('char_code', $currencyRate->charCodeUpper())->firstOrNew();

        $attributes = [
            'char_code' => $currencyRate->charCodeUpper(),
            'name' => $currencyRate->name(),
            'rate' => $currencyRate->unitPrice(),
            'relevant_on' => $currencyRate->relevantOn(),
        ];

        if ($_currency->id) {
            $_currency->saveToHistory();
        }

        $_currency->fill($attributes)->save();

        return $_currency;
    }
}
