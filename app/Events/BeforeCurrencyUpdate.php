<?php

namespace App\Events;

use App\Models\Currency;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeforeCurrencyUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $currency;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }
}
