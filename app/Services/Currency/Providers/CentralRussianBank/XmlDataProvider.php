<?php

namespace App\Services\Currency\Providers\CentralRussianBank;

use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Contracts\IDataProvider;
use App\Services\Currency\Exceptions\CurrencyRateNotFoundException;
use App\Services\Currency\Exceptions\DataProviderException;
use App\Services\Currency\Models\CurrencyRate;
use App\Services\Currency\Providers\CentralRussianBank\Exceptions\InvalidResponseException;

class XmlDataProvider implements IDataProvider
{
    private $client;
    private $builder;

    public function __construct(XmlClient $client, XmlBuilder $builder)
    {
        $this->client = $client;
        $this->builder = $builder;
    }

    public function fetchCurrencyRates(): CurrencyRateCollection
    {
        return $this->makeCurrencyRateCollection($this->fetchXml());
    }

    public function fetchCurrencyRateByCharCode(string $charCode): CurrencyRate
    {
        return $this->makeCurrencyRateByCharCode($this->fetchXml(), $charCode);
    }

    protected function fetchXml(): \SimpleXMLElement
    {
        try {
            return $this->client->fetchXml();
        } catch (InvalidResponseException $exception) {
            $error = sprintf('Cannot fetch data from Central Bank of Russia: %s', $exception->getMessage());
            throw new DataProviderException($error);
        }
    }

    protected function makeCurrencyRateCollection(\SimpleXMLElement $xml): CurrencyRateCollection
    {
        return $this->builder->makeCurrencyRateCollection($xml);
    }

    protected function makeCurrencyRateByCharCode(\SimpleXMLElement $xml, string $charCode): CurrencyRate
    {
        $currencyRate = $this->builder->makeCurrencyRateByCharCode($xml, $charCode);

        if (!$currencyRate) {
            throw new CurrencyRateNotFoundException('Invalid char code');
        }

        return $currencyRate;
    }
}
