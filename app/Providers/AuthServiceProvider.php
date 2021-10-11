<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        
        ## REGISTRO ##
        Gate::define('registro-view', 'App\Policies\RegistroPolicy@view');
        Gate::define('registro-create', 'App\Policies\RegistroPolicy@create');
        Gate::define('registro-edit', 'App\Policies\RegistroPolicy@edit');


    }
}
