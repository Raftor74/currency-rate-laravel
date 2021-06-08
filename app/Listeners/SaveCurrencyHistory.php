<?php

namespace App\Listeners;

use App\Events\BeforeCurrencyUpdate;

class SaveCurrencyHistory
{
    /**
     * Handle the event.
     *
     * @param  BeforeCurrencyUpdate  $event
     * @return void
     */
    public function handle(BeforeCurrencyUpdate $event)
    {
        $event->currency->saveToHistory();
    }
}
