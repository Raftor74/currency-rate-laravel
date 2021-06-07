<?php

namespace App\Services\Currency\Providers\CentralRussianBank\Models;

use Carbon\Carbon;

class CurrencyRateXmlNode
{
    private $node;
    private $relevantOn;

    public function __construct(\SimpleXMLElement $node, ?Carbon $relevantOn = null)
    {
        $this->node = $node;
        $this->relevantOn = $relevantOn;
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

    public function relevantOn(): ?Carbon
    {
        return $this->relevantOn;
    }
}
