<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\CompanyUser;
use Maitiigor\RC\Models\CompanyUserUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompanyUserUpdatedListener
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
     * @param  CompanyUserUpdated  $event
     * @return void
     */
    public function handle(CompanyUserUpdated $event)
    {
        //
    }
}
