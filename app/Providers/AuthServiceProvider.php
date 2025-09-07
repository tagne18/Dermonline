<?php

namespace App\Providers;

use App\Models\NewAnnonce;
use App\Policies\NewAnnoncePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        NewAnnonce::class => NewAnnoncePolicy::class,
        \App\Models\Planning::class => \App\Policies\PlanningPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
