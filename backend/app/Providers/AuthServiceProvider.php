<?php

namespace App\Providers;

use App\Services\MatchServices\UserRolesService;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\Document' => 'App\Policies\DocumentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('getDataRequiredForDocument', function (User $user) {

            return UserRolesService::check($user, UserRolesService::MANAGER);

        });

        Gate::define('getDocument', function (User $user) {
            return UserRolesService::check($user, UserRolesService::MANAGER);
        });
    }
}
