<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\LogRadiusEventToRadpostauth;
use App\Listeners\RecordFailedLoginAttempt;
use App\Listeners\LogAppLoginToRadpostauth;
use App\Events\LoginAttemptRecorded;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LogAppLoginToRadpostauth::class,
            \App\Listeners\RecordLoginAttempt::class,
        ],
        Failed::class => [
            RecordFailedLoginAttempt::class,
        ],
        LoginAttemptRecorded::class => [
            LogRadiusEventToRadpostauth::class,
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