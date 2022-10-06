<?php

namespace App\Models\Traits;

use App\Models\Tenant;

trait UserACLTrait
{
    // cruza as permissoes do PLANO com as permissoes do CARGO
    public function permissions(): array
    {
        $permissionsPlan = $this->permissionsPlan();
        $permissionsRole = $this->permissionsRole();

        $permissions = [];

        foreach ($permissionsRole as $permission) {
            // se a permissao da role estiver dentro das permissoes do plano
            if (in_array($permission, $permissionsPlan)) {
                array_push($permissions, $permission);
            }
        }

        return $permissions;
    }


    // retorna todas as permissoes do PLANO do usuário logado
    public function permissionsPlan(): array
    {
        $tenant = Tenant::with('plan.profiles.permissions')->where('id', $this->tenant_id)->first();
        $plan = $tenant->plan;

        $permissions = [];
        foreach ($plan->profiles as $profile) {
            foreach ($profile->permissions as $permission) {
                array_push($permissions, $permission->name);
            }
        }

        return $permissions;
    }

    // retorna todas as permissoes do CARGO(Role) do usuário logado
    public function permissionsRole(): array 
    {
        $roles = $this->roles()->with('permissions')->get();
        $permissions = [];

        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                array_push($permissions, $permission->name);
            }
        }

        return $permissions;
    }

    // verifica se o usuario possui uma permissão específica
    public function hasPermission(string $permissionName): bool
    {
        return in_array($permissionName, $this->permissions());
    }

    // todos os emails listados no arquivo config/acl.php
    // terão a cesso a todo o sistema (super usuario)
    public function isAdmin(): bool 
    {
        return in_array($this->email, config('acl.admins'));;
    }

    // negação do metodo 'isAdmin'
    public function isTenant(): bool 
    {
        return !in_array($this->email, config('acl.admins'));;
    }
}
