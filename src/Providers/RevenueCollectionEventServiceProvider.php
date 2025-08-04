<?php

namespace Maitiigor\RC\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Maitiigor\Payroll\Listeners\PayrollJobCompletionListener;

class RevenueCollectionEventServiceProvider extends ServiceProvider
{

    protected $listen = [
      
    ];

    public function boot()
    {
        parent::boot();
    }
}