<?php

namespace App\Console\Commands;

use App\Services\Currency\Contracts\IDataProvider;
use App\Services\Currency\CurrencyRateStorage;
use Illuminate\Console\Command;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency_rates:update {char_code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency rates';

    /** @var IDataProvider */
    protected $dataProvider;

    /** @var CurrencyRateStorage */
    protected $storage;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IDataProvider $dataProvider, CurrencyRateStorage $storage)
    {
        parent::__construct();
        $this->dataProvider = $dataProvider;
        $this->storage = $storage;
    }

    public function handle()
    {
        $charCode = $this->argument('char_code');

        if (!is_null($charCode)) {
            return $this->updateByCharCode(strtoupper($charCode));
        }

        $this->update();
    }

    protected function update()
    {
        $this->info('Starting to update currencies');
        $collection = $this->dataProvider->fetchCurrencyRates();
        $this->storage->storeCurrencyRateCollection($collection);
        $this->info('Done');
    }

    protected function updateByCharCode(string $charCode)
    {
        $this->info(sprintf('Starting to update the currency with char code %s', $charCode));
        $currency = $this->storage->storeCurrencyRate($this->dataProvider->fetchCurrencyRateByCharCode($charCode));
        $this->info('Done');
        return $currency;
    }
}
