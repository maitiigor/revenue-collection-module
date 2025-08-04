<?php

namespace Maitiigor\RC\Events;

use Maitiigor\RC\Models\InvoiceItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceItemCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoiceItem;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(InvoiceItem $invoiceItem)
    {
        $this->invoiceItem = $invoiceItem;
    }

}
