<?php


namespace Tests\Feature\Currency\Commands;

use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Contracts\IDataProvider;
use App\Services\Currency\Models\CurrencyRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateCurrencyRatesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function command_correct_update_currency_by_char_code()
    {
        /** @var Currency $currency */
        $currency = Currency::factory()->create(['char_code' => 'USD']);
        $currencyRate = CurrencyRate::make('USD', 'Доллар', 10, 780.0);
        $dataProviderMock = Mockery::mock(IDataProvider::class, function (MockInterface $mock) use ($currencyRate) {
            $mock->shouldReceive('fetchCurrencyRateByCharCode')
                ->andReturn($currencyRate);
        });
        $this->instance(IDataProvider::class, $dataProviderMock);

        $this->artisan('currency_rates:update', ['char_code' => 'USD']);

        $history = $currency->history()->first();
        $historyCount = CurrencyHistory::query()->count();

        $this->assertEquals(1, $historyCount);
        $this->assertEquals($history->rate, $currency->rate);

        $currency->refresh();

        $this->assertEquals(78.0, $currency->rate);
        $this->assertEquals('USD', $currency->char_code);
        $this->assertEquals('Доллар', $currency->name);
    }

    /** @test */
    public function command_correct_update_currencies()
    {
        $collection = (new CurrencyRateCollection())
            ->add(CurrencyRate::make('USD', 'Доллар', 10, 780.0))
            ->add(CurrencyRate::make('EUR', 'Евро', 1, 90.0));
        $dataProviderMock = Mockery::mock(IDataProvider::class, function (MockInterface $mock) use ($collection) {
            $mock->shouldReceive('fetchCurrencyRates')
                ->andReturn($collection);
        });
        $this->instance(IDataProvider::class, $dataProviderMock);

        $this->artisan('currency_rates:update');

        $currencies = Currency::all();

        $this->assertEquals(2, $currencies->count());
        $this->assertTrue(in_array('USD', $currencies->pluck('char_code')->all()));
        $this->assertTrue(in_array('EUR', $currencies->pluck('char_code')->all()));
    }

}
