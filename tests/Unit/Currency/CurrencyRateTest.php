<?php

namespace Tests\Unit\Currency;

use App\Services\Currency\Models\CurrencyRate;
use PHPUnit\Framework\TestCase;

class CurrencyRateTest extends TestCase
{
    /**
     * @test
     * @dataProvider values
     */
    public function unit_price_calculated_correctly(float $expected, int $quantity, float $price)
    {
        $currencyRate = CurrencyRate::make('AMD', 'Test currency', $quantity, $price);

        $this->assertEquals($expected, $currencyRate->unitPrice());
    }

    /**
     * - Цена за единицу
     * - Кол-во
     * - Цена за указанное кол-во
     * @return array[]
     */
    public function values(): array
    {
        return [
            [0.14, 100, 14.0854],
            [14.43, 1, 14.4293],
            [1, 10, 10.0242]
        ];
    }
}
