<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\ComplianceReport;
use Maitiigor\RC\Models\ComplianceReportCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComplianceReportCreatedListener
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
     * @param  ComplianceReportCreated  $event
     * @return void
     */
    public function handle(ComplianceReportCreated $event)
    {
        //
    }
}
