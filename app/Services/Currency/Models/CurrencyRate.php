<?php

namespace App\Services\Currency\Models;

use App\Services\Currency\Exceptions\InvalidParameterException;
use Carbon\Carbon;

class CurrencyRate
{
    private $charCode;
    private $name;
    private $quantity;
    private $price;
    private $relevantOn;

    public function __construct(string $charCode, string $name, int $quantity, float $price, Carbon $relevantOn = null)
    {
        if ($quantity < 1) {
            throw new InvalidParameterException('Quantity cannot be less than 1');
        }

        $this->name = $name;
        $this->quantity = $quantity;
        $this->charCode = $charCode;
        $this->price = $price;
        $this->relevantOn = $relevantOn ?? now();
    }

    public function charCodeUpper(): string
    {
        return strtoupper($this->charCode);
    }

    public function charCode(): string
    {
        return $this->charCode;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function unitPrice(int $precision = 2): float
    {
        return round($this->price / $this->quantity, $precision);
    }

    public function relevantOn(): Carbon
    {
        return $this->relevantOn;
    }
}
