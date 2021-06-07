<?php

namespace App\Services\Currency\Providers\CentralRussianBank\Models;

class CurrencyRateXmlNode
{
    private $node;

    public function __construct(\SimpleXMLElement $node)
    {
        $this->node = $node;
    }

    public function charCode(): string
    {
        return (string)$this->node->CharCode;
    }

    public function name(): string
    {
        return (string)$this->node->Name;
    }

    public function quantity(): int
    {
        return (int)$this->node->Nominal;
    }

    public function price(): float
    {
        $value = (string)$this->node->Value;
        return (float)str_replace(',', '.', $value);
    }
}
