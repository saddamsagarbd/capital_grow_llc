<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\CreateAccount;
use Illuminate\Auth\Listeners\CreateAccountFired;
use Illuminate\Auth\Events\CreateUserProfile;
use Illuminate\Auth\Listeners\CreateUserProfileFired;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\CreateAccount' => [
            'App\Listeners\CreateAccountFired'
        ],
        'App\Events\CreateUserProfile' => [
            'App\Listeners\CreateUserProfileFired'
        ],
        'App\Events\StoreUserAddress' => [
            'App\Listeners\StoreAddressFired'
        ],
        'App\Events\TransactionEvent' => [
            'App\Listeners\TransactionEventFired'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
