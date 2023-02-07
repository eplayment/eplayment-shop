<?php

namespace Epaygames\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('sales.invoice.save.after', 'Epaygames\Listeners\Transaction@saveTransaction');
    }
}