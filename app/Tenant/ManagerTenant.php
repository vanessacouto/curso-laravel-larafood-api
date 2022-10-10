<?php

namespace App\Tenant;

use App\Models\Tenant;

class ManagerTenant
{
    public function getTenantIdentify()
    {
        // para fazer um pedido, o usuario pode ou nao estar logado
        return auth()->check() ? auth()->user()->tenant_id : '';
    }

    public function getTenant()
    {
        return auth()->check() ? auth()->user()->tenant : '';
    }

    public function isAdmin(): bool
    {
        // verifica se o email autenticado está listado
        // como admin no arquivo config/tenant.php
        return in_array(auth()->user()->email, config('tenant.admins'));
    }
}
