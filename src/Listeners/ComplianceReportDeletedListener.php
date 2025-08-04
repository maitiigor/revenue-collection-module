<?php

namespace Maitiigor\RC\Listeners;

use Maitiigor\RC\Models\ComplianceReport;
use Maitiigor\RC\Models\ComplianceReportDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComplianceReportDeletedListener
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
     * @param  ComplianceReportDeleted  $event
     * @return void
     */
    public function handle(ComplianceReportDeleted $event)
    {
        //
    }
}
