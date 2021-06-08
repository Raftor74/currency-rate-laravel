<?php

namespace App\Providers;

use App\Events\BeforeCurrencyUpdate;
use App\Listeners\SaveCurrencyHistory;
use App\Services\Currency\Contracts\IDataProvider;
use App\Services\Currency\Providers\CentralRussianBank\XmlDataProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(IDataProvider::class, XmlDataProvider::class);

        Event::listen(BeforeCurrencyUpdate::class, [SaveCurrencyHistory::class, 'handle']);
    }
}
