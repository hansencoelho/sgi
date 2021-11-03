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
    // protected $policies = [
       
    //     'App\Usuario' => 'App\Policies\UsuarioPolicy',
    //     'App\Acessso' => 'App\Policies\LinhaMovelPolicy',
        
    // ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ## ACESSO ##
        Gate::define('acesso-view', 'App\Policies\AcessoPolicy@view');
        Gate::define('acesso-create', 'App\Policies\AcessoPolicy@create');
        Gate::define('acesso-edit', 'App\Policies\AcessoPolicy@edit'); 

        ## USUÁRIO ##
        Gate::define('usuario-view', 'App\Policies\UsuarioPolicy@view');
        Gate::define('usuario-create', 'App\Policies\UsuarioPolicy@create');
        Gate::define('usuario-edit', 'App\Policies\UsuarioPolicy@edit'); 

        ## REGISTRO ##
        Gate::define('registro-view', 'App\Policies\RegistroPolicy@view');
        Gate::define('registro-create', 'App\Policies\RegistroPolicy@create');
        Gate::define('registro-edit', 'App\Policies\RegistroPolicy@edit');

    }
}
