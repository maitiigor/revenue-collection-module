<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\Invoice;
use Maitiigor\RC\Models\InvoiceDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceDeletedListener
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
     * @param  InvoiceDeleted  $event
     * @return void
     */
    public function handle(InvoiceDeleted $event)
    {
        //
    }
}
