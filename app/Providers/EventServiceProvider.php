<?php

namespace App\Providers;

use Illuminate\Auth\Events\Authenticated;
use App\Notifications\UserLoggedIn;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        
        // 1. Is this present?
        Authenticated::class => [ 
            function ($event) {
                if ($event->user) {
                    // 2. Is your Notification Class imported?
                    $event->user->notify(new UserLoggedIn($event->user));
                }
            }
        ],
    ];

    /**
     * Register any events for your application here.
     */
    public function boot(): void
    {
        // 3. Is parent::boot() called?
        parent::boot();
    }
}