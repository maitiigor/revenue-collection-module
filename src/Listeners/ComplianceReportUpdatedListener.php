<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\ComplianceReport;
use Maitiigor\RC\Models\ComplianceReportUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComplianceReportUpdatedListener
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
     * @param  ComplianceReportUpdated  $event
     * @return void
     */
    public function handle(ComplianceReportUpdated $event)
    {
        //
    }
}
