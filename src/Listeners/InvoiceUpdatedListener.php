<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\Invoice;
use Maitiigor\RC\Models\InvoiceUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvoiceUpdated  $event
     * @return void
     */
    public function handle(InvoiceUpdated $event)
    {
        //
    }
}
