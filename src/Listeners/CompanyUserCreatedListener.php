<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\CompanyUser;
use Maitiigor\RC\Models\CompanyUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompanyUserCreatedListener
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
     * @param  CompanyUserCreated  $event
     * @return void
     */
    public function handle(CompanyUserCreated $event)
    {
        //
    }
}
