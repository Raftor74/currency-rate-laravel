<?php

namespace Tests\Feature\Currency;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function currency_keeps_history_correctly()
    {
        /** @var Currency $currency */
        $currency = Currency::factory()->create();

        $history = $currency->saveToHistory();

        $this->assertNotEmpty($history);
        $this->assertEquals($currency->rate, $history->rate);
        $this->assertEquals($currency->relevant_on, $history->relevant_on);
    }
}
