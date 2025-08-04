<?php

namespace Maitiigor\RC\Events;

use Maitiigor\RC\Models\ComplianceReport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplianceReportDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $complianceReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ComplianceReport $complianceReport)
    {
        $this->complianceReport = $complianceReport;
    }

}
