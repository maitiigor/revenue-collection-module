<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\InvoiceItem;
use Maitiigor\RC\Models\InvoiceItemUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceItemUpdatedListener
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
     * @param  InvoiceItemUpdated  $event
     * @return void
     */
    public function handle(InvoiceItemUpdated $event)
    {
        //
    }
}
