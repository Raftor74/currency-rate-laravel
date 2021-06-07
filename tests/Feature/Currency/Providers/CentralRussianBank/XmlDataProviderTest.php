<?php

namespace Tests\Feature\Currency\Providers\CentralRussianBank;

use App\Services\Currency\Exceptions\CurrencyRateNotFoundException;
use App\Services\Currency\Providers\CentralRussianBank\XmlClient;
use App\Services\Currency\Providers\CentralRussianBank\XmlDataProvider;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class XmlDataProviderTest extends TestCase
{
    /** @test */
    public function provider_return_correct_currency_rate()
    {
        $charCode = 'EUR';
        $xml = $this->getTestXml();
        $xmlClientMock = Mockery::mock(XmlClient::class, function (MockInterface $mock) use ($xml) {
            $mock->makePartial()
                ->shouldReceive('fetchContent')
                ->andReturn($xml);
        });

        $this->instance(XmlClient::class, $xmlClientMock);
        /** @var XmlDataProvider $dataProvider */
        $dataProvider = $this->app->make(XmlDataProvider::class);

        $currencyRate = $dataProvider->fetchCurrencyRateByCharCode($charCode);

        $this->assertEquals($charCode, $currencyRate->charCode());
        $this->assertEquals('Евро', $currencyRate->name());
        $this->assertEquals(88.6530, $currencyRate->price());
        $this->assertEquals(1, $currencyRate->quantity());
    }

    /** @test */
    public function provider_return_correct_currency_rate_collection()
    {
        $xml = $this->getTestXml();
        $xmlClientMock = Mockery::mock(XmlClient::class, function (MockInterface $mock) use ($xml) {
            $mock->makePartial()
                ->shouldReceive('fetchContent')
                ->andReturn($xml);
        });

        $this->instance(XmlClient::class, $xmlClientMock);
        /** @var XmlDataProvider $dataProvider */
        $dataProvider = $this->app->make(XmlDataProvider::class);

        $currencyRateCollection = $dataProvider->fetchCurrencyRates();

        $this->assertCount(3, $currencyRateCollection->all());
    }

    /** @test */
    public function provider_raise_not_found_currency_rate_exception_with_invalid_char_code()
    {
        $this->expectException(CurrencyRateNotFoundException::class);

        $charCode = '9999';
        $xml = $this->getTestXml();
        $xmlClientMock = Mockery::mock(XmlClient::class, function (MockInterface $mock) use ($xml) {
            $mock->makePartial()
                ->shouldReceive('fetchContent')
                ->andReturn($xml);
        });

        $this->instance(XmlClient::class, $xmlClientMock);
        /** @var XmlDataProvider $dataProvider */
        $dataProvider = $this->app->make(XmlDataProvider::class);
        $dataProvider->fetchCurrencyRateByCharCode($charCode);
    }

    protected function getTestXml(): string
    {
        return '<?xml version="1.0"?>
<ValCurs Date="08.06.2021" name="Foreign Currency Market">
   <Valute ID="R01235">
    <NumCode>840</NumCode>
    <CharCode>USD</CharCode>
    <Nominal>1</Nominal>
    <Name>Американский доллар</Name>
    <Value>72,9294</Value>
  </Valute>
  <Valute ID="R01239">
    <NumCode>978</NumCode>
    <CharCode>EUR</CharCode>
    <Nominal>1</Nominal>
    <Name>Евро</Name>
    <Value>88,6530</Value>
  </Valute>
  <Valute ID="R01270">
    <NumCode>356</NumCode>
    <CharCode>INR</CharCode>
    <Nominal>10</Nominal>
    <Name>Индийских рупий</Name>
    <Value>10,0143</Value>
  </Valute>
</ValCurs>';
    }
}
