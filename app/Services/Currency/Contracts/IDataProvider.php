<?php

namespace App\Services\Currency\Contracts;

use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Exceptions\DataProviderException;
use App\Services\Currency\Models\CurrencyRate;

interface IDataProvider
{
    /**
     * Получает список курсов валют по отношению к рублю
     * @return CurrencyRateCollection
     * @throws DataProviderException
     */
    public function fetchCurrencyRates(): CurrencyRateCollection;

    /**
     * Получает курс валюты по отношению к рублю
     * @param string $charCode
     * @return CurrencyRate
     * @throws DataProviderException
     */
    public function fetchCurrencyRateByCharCode(string $charCode): CurrencyRate;
}
