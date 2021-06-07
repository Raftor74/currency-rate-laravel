<?php


namespace App\Services\Currency\Providers\CentralRussianBank;

use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Models\CurrencyRate;
use App\Services\Currency\Providers\CentralRussianBank\Models\CurrencyRateXmlNode;
use Carbon\Carbon;

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

        return count($matches)
            ? $this->makeCurrencyRate(array_shift($matches))
            : null;
    }

    protected function makeCurrencyRateXmlNodes(\SimpleXMLElement $xml): array
    {
        $nodes = [];
        $relevantOn = $this->makeRelevantOnDate($xml);
        $childNodes = $xml->children();

        foreach ($childNodes as $node) {
            $nodes[] = new CurrencyRateXmlNode($node, $relevantOn);
        }

        return $nodes;
    }

    protected function makeCurrencyRate(CurrencyRateXmlNode $node): ?CurrencyRate
    {
        if ($node->quantity() < 1) {
            return null;
        }

        return CurrencyRate::make(
            $node->charCode(),
            $node->name(),
            $node->quantity(),
            $node->price(),
        );
    }

    protected function makeRelevantOnDate(\SimpleXMLElement $xml): ?Carbon
    {
        return isset($xml->attributes()['Date'])
            ? Carbon::parse($xml->attributes()['Date'])
            : null;
    }
}
