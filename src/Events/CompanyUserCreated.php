<?php

namespace Maitiigor\RC\Events;

use Maitiigor\RC\Models\CompanyUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyUserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $companyUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CompanyUser $companyUser)
    {
        $this->companyUser = $companyUser;
    }

}
