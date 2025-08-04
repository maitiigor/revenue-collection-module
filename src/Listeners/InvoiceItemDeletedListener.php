<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\InvoiceItem;
use Maitiigor\RC\Models\InvoiceItemDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceItemDeletedListener
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
     * @param  InvoiceItemDeleted  $event
     * @return void
     */
    public function handle(InvoiceItemDeleted $event)
    {
        //
    }
}
