<?php

namespace App\Listeners;

use App\Events\StoreUserAddress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreAddressFired
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
     * @param  \App\Events\StoreUserAddress  $event
     * @return void
     */
    public function handle(StoreUserAddress $event)
    {
        //
    }
}
