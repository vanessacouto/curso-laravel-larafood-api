<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        $permissions = Permission::all();

        // itera todas as permissoes do sistema, definindo os gates de acordo com as permissoes do usuario autenticado
        foreach ($permissions as $permission) {
            // o parametro 'user' é o usuario autenticado (ele é injetado automaticamente)
            Gate::define(
                $permission->name, function (User $user) use ($permission) {
                    return $user->hasPermission($permission->name);
                }
            );
        }

        // 'owner' é só o nome do gate...pode ser qualquer nome
        Gate::define(
            'owner', function (User $user, $object) {
                // se usar esse gate no controller, por exemplo,
                // só deixaria alterar o registro caso o usuario logado foi o quem criou o registro
                return $user->id === $object->id; 
            }
        );

        // o gate before sera aplicado antes que qualquer 'Gate::define'
        Gate::before(
            function (User $user) {
                if ($user->isAdmin()) {
                    return true;
                }
            }
        );
    }
}
