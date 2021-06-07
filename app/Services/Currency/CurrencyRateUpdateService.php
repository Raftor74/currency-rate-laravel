<?php

namespace App\Services\Currency;

use App\Models\Currency;
use App\Services\Currency\Contracts\IDataProvider;

class CurrencyRateUpdateService
{
    protected $dataProvider;
    protected $storage;

    public function __construct(IDataProvider $dataProvider, CurrencyRateStorage $storage)
    {
        $this->dataProvider = $dataProvider;
        $this->storage = $storage;
    }

    /**
     * Обновляет все курсы валют
     * @throws Exceptions\DataProviderException
     */
    public function update()
    {
        $collection = $this->dataProvider->fetchCurrencyRates();
        $this->storage->storeCurrencyRateCollection($collection);
    }

    /**
     * Обновляет курс валюты по его символьному коду
     * @param string $charCode
     * @return Currency
     * @throws Exceptions\DataProviderException
     */
    public function updateByCharCode(string $charCode): Currency
    {
        $currencyRate = $this->dataProvider->fetchCurrencyRateByCharCode($charCode);
        return $this->storage->storeCurrencyRate($currencyRate);
    }
}
