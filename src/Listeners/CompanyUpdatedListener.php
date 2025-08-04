<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\Company;
use Maitiigor\RC\Models\CompanyUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompanyUpdatedListener
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
     * @param  CompanyUpdated  $event
     * @return void
     */
    public function handle(CompanyUpdated $event)
    {
        //
    }
}
