<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        Passport::tokensExpireIn(now()->addSecond(60));
        Passport::tokensCan([
            'create-post' => 'Registrar un nuevo post',
            'read-post' => 'Obtener los posts registrados',
            'update-post' => 'Actualizar posts',
            'delete-post' => 'Eliminar posts'
        ]);

        Passport::setDefaultScope([
            'read-post'
        ]);
        //
    }
}
