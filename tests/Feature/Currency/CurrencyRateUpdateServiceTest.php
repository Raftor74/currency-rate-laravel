<?php

namespace Tests\Feature\Currency;

use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\Services\Currency\Collections\CurrencyRateCollection;
use App\Services\Currency\Contracts\IDataProvider;
use App\Services\Currency\CurrencyRateUpdateService;
use App\Services\Currency\Models\CurrencyRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery\MockInterface;

class CurrencyRateUpdateServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function service_correctly_update_currency_rate_by_char_code_without_history()
    {
        $currencyCharCode = 'USD';
        $currencyRate = new CurrencyRate($currencyCharCode, 'Доллар США', 1, 74);
        $dataProvider = $this->mock(
            IDataProvider::class,
            function (MockInterface $mock) use ($currencyCharCode, $currencyRate) {
                $mock->shouldReceive('fetchCurrencyRateByCharCode')
                    ->with($currencyCharCode)
                    ->andReturn($currencyRate);
            }
        );
        $this->instance(IDataProvider::class, $dataProvider);
        $service = $this->app->make(CurrencyRateUpdateService::class);

        /** @var Currency $currency */
        $currency = $service->updateByCharCode($currencyCharCode)->refresh();

        $this->assertEquals($currency->char_code, $currencyRate->charCodeUpper());
        $this->assertEquals($currency->name, $currencyRate->name());
        $this->assertEquals($currency->rate, $currencyRate->unitPrice());
    }

    /** @test */
    public function service_correctly_update_currency_rate_by_char_code_with_history()
    {
        // arrange
        $currencyCharCode = 'USD';
        $currencyRate = new CurrencyRate($currencyCharCode, 'Доллар США', 1, 74);
        $existedCurrency = Currency::factory()->create([
            'char_code' => $currencyCharCode,
            'rate' => 80.5,
        ]);
        $dataProvider = $this->mock(
            IDataProvider::class,
            function (MockInterface $mock) use ($currencyCharCode, $currencyRate) {
                $mock->shouldReceive('fetchCurrencyRateByCharCode')
                    ->with($currencyCharCode)
                    ->andReturn($currencyRate);
            }
        );
        $this->instance(IDataProvider::class, $dataProvider);
        /** @var CurrencyRateUpdateService $service */
        $service = $this->app->make(CurrencyRateUpdateService::class);

        // act
        $updatedCurrency = $service->updateByCharCode($currencyCharCode)->refresh();

        // assert
        $history = $updatedCurrency->history()->first();
        $historyCount = CurrencyHistory::query()->count();

        $this->assertNotEmpty($history);
        $this->assertEquals(1, $historyCount);
        $this->assertEquals($history->rate, $existedCurrency->rate);
        $this->assertEquals($existedCurrency->id, $updatedCurrency->id);
        $this->assertEquals($updatedCurrency->char_code, $currencyRate->charCodeUpper());
        $this->assertEquals($updatedCurrency->name, $currencyRate->name());
        $this->assertEquals($updatedCurrency->rate, $currencyRate->unitPrice());
    }

    /** @test  */
    public function service_correctly_update_currency_rate_collection()
    {
        // arrange
        $existedCurrencyCharCode = 'USD';
        $newCurrencyCharCode = 'AZK';
        $existedCurrencyRate = new CurrencyRate($existedCurrencyCharCode, 'Доллар США', 1, 74);
        $newCurrencyRate = new CurrencyRate($newCurrencyCharCode, 'Тестовая валюта', 1, 32);
        $currencyRateCollection = (new CurrencyRateCollection())
            ->add($existedCurrencyRate)
            ->add($newCurrencyRate);
        /** @var Currency $existedCurrency */
        $existedCurrency = Currency::factory()->create([
            'char_code' => $existedCurrencyCharCode,
            'rate' => 80.5,
        ]);
        $dataProvider = $this->mock(
            IDataProvider::class,
            function (MockInterface $mock) use ($currencyRateCollection) {
                $mock->shouldReceive('fetchCurrencyRates')
                    ->andReturn($currencyRateCollection);
            }
        );
        $this->instance(IDataProvider::class, $dataProvider);
        /** @var CurrencyRateUpdateService $service */
        $service = $this->app->make(CurrencyRateUpdateService::class);

        // act
        $service->update();

        //assert
        $newCurrency = Currency::query()->where('char_code', $newCurrencyCharCode)->first();
        $history = $existedCurrency->history()->first();
        $historyCount = CurrencyHistory::query()->count();

        $this->assertEquals($existedCurrency->rate, $history->rate);
        $this->assertEquals(1, $historyCount);

        $existedCurrency->refresh();

        $this->assertNotEmpty($newCurrency);
        $this->assertEquals($existedCurrency->rate, $existedCurrencyRate->unitPrice());
        $this->assertEquals($newCurrency->rate, $newCurrencyRate->unitPrice());
    }
}
