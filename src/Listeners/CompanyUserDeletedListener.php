<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\CompanyUser;
use Maitiigor\RC\Models\CompanyUserDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompanyUserDeletedListener
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
     * @param  CompanyUserDeleted  $event
     * @return void
     */
    public function handle(CompanyUserDeleted $event)
    {
        //
    }
}
