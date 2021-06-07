<?php


namespace App\Services\Currency\Providers\CentralRussianBank;

use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Exceptions\InvalidParameterException;
use App\Services\Currency\Models\CurrencyRate;
use App\Services\Currency\Providers\CentralRussianBank\Models\CurrencyRateXmlNode;

class XmlBuilder
{
    public function makeCurrencyRateCollection(\SimpleXMLElement $xml): CurrencyRateCollection
    {
        $collection = new CurrencyRateCollection();
        $items = $this->makeCurrencyRateXmlNodes($xml);

        foreach ($items as $item) {
            $currencyRate = $this->makeCurrencyRate($item);

            if (!is_null($currencyRate)) {
                $collection->add($currencyRate);
            }
        }

        return $collection;
    }

    public function makeCurrencyRateByCharCode(\SimpleXMLElement $xml, string $charCode): ?CurrencyRate
    {
        $items = $this->makeCurrencyRateXmlNodes($xml);
        $matches = array_filter($items, function (CurrencyRateXmlNode $node) use ($charCode) {
            return $node->charCode() === $charCode;
        });

        if (!count($matches)) {
            return null;
        }

        return $this->makeCurrencyRate(array_shift($matches));
    }

    protected function makeCurrencyRateXmlNodes(\SimpleXMLElement $xml): array
    {
        $nodes = [];
        $childNodes = $xml->children();

        foreach ($childNodes as $node) {
            $nodes[] = new CurrencyRateXmlNode($node);
        }

        return $nodes;
    }

    protected function makeCurrencyRate(CurrencyRateXmlNode $node): ?CurrencyRate
    {
        try {
            return new CurrencyRate(
                $node->charCode(),
                $node->name(),
                $node->quantity(),
                $node->price(),
            );
        } catch (InvalidParameterException $exception) {
            return null;
        }
    }
}
