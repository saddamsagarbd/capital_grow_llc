<?php

namespace App\Listeners;

use App\Events\CreateAccount;
use App\Models\UserAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAccountFired
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
     * @param  CreateAccount  $event
     * @return void
     */
    public function handle(CreateAccount $event)
    {
        $ac_info = [
            'user_id' => $event->data->user_id,
            'account_number' => $event->data->contact_number,
            'account_title' => $event->data->first_name.' '.$event->data->last_name,
            'account_type' => 1,
            'account_status' => 1
        ];
        UserAccount::create($ac_info);
    }
}
