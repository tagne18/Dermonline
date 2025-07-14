<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\CheckBlockedUserOnLogin;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            CheckBlockedUserOnLogin::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
