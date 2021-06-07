<?php

namespace App\Services\Currency\Models;

use App\Services\Currency\Exceptions\InvalidParameterException;

class CurrencyRate
{
    private $charCode;
    private $quantity;
    private $price;

    public function __construct(string $charCode, int $quantity, float $price)
    {
        if ($quantity < 1) {
            throw new InvalidParameterException('Quantity cannot be less than 1');
        }

        $this->quantity = $quantity;
        $this->charCode = $charCode;
        $this->price = $price;
    }

    public function charCode(): string
    {
        return $this->charCode;
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
}
