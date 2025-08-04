<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\InvoiceItem;
use Maitiigor\RC\Models\InvoiceItemCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceItemCreatedListener
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
     * @param  InvoiceItemCreated  $event
     * @return void
     */
    public function handle(InvoiceItemCreated $event)
    {
        //
    }
}
