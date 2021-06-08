<?php

namespace App\Listeners;

use App\Events\BeforeCurrencyUpdated;

class SaveCurrencyHistory
{
    /**
     * Handle the event.
     *
     * @param  BeforeCurrencyUpdated  $event
     * @return void
     */
    public function handle(BeforeCurrencyUpdated $event)
    {
        $event->currency->saveToHistory();
    }
}
