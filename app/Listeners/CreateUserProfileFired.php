<?php

namespace App\Listeners;

use App\Events\CreateUserProfile;
use App\Models\Userprofile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserProfileFired
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
     * @param  \App\Events\CreateUserProfile  $event
     * @return void
     */
    public function handle(CreateUserProfile $event)
    {
        $user_profile = [
            'user_id' => $event->data->user_id,
            'reference_id' => isset($event->data->refer_username)?$event->data->refer_username:'',
            'placement_id' => isset($event->data->placement_username)?$event->data->placement_username:'',
            'first_name' => $event->data->first_name,
            'last_name' => $event->data->last_name,
            'gender' => $event->data->gender,
            'identification_no' => $event->data->identification_no,
            'dob' => $event->data->dob,
            'profile_img' => ($event->data->filename != "")?$event->data->filename:'',
            'contact_number' => $event->data->contact_number,
        ];
        $user_profile = array_filter($user_profile);
        Userprofile::create($user_profile);
    }
}
