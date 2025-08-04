<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\Company;
use Maitiigor\RC\Models\CompanyDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompanyDeletedListener
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
     * @param  CompanyDeleted  $event
     * @return void
     */
    public function handle(CompanyDeleted $event)
    {
        //
    }
}
