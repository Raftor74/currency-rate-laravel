<?php

namespace App\Services\Currency;

use App\Events\BeforeCurrencyUpdated;
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
            $this->updateCurrencyRate($currencyRate, $currency);
            $existedCharCodes[] = $charCode;
        }

        $nonExistedCharCodes = array_diff($charCodes, $existedCharCodes);

        foreach ($nonExistedCharCodes as $charCode) {
            $currencyRate = $collection->getByCharCode($charCode);
            $this->createCurrencyRate($currencyRate);
        }
    }

    public function createCurrencyRate(CurrencyRate $currencyRate): Currency
    {
        $attributes = $this->makeCurrencyRateStoreAttributes($currencyRate);

        /** @var Currency $currency */
        $currency = Currency::query()->create($attributes);

        return $currency;
    }

    public function updateCurrencyRate(CurrencyRate $currencyRate, Currency $currency): Currency
    {
        BeforeCurrencyUpdated::dispatch($currency);

        $attributes = $this->makeCurrencyRateStoreAttributes($currencyRate);
        $currency->fill($attributes)->save();

        return $currency;
    }

    public function updateOrCreateCurrencyRate(CurrencyRate $currencyRate): Currency
    {
        /** @var Currency $currency */
        $currency = Currency::query()
            ->where('char_code', $currencyRate->charCodeUpper())
            ->first();

        return ($currency)
            ? $this->updateCurrencyRate($currencyRate, $currency)
            : $this->createCurrencyRate($currencyRate);
    }

    public function makeCurrencyRateStoreAttributes(CurrencyRate $currencyRate): array
    {
        return [
            'char_code' => $currencyRate->charCodeUpper(),
            'name' => $currencyRate->name(),
            'rate' => $currencyRate->unitPrice(),
            'relevant_on' => $currencyRate->relevantOn(),
        ];
    }
}
