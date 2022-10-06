<?php

namespace App\Tenant;

use App\Models\Tenant;

class ManagerTenant
{
    public function getTenantIdentify()
    {
        return auth()->user()->tenant_id;
    }

    public function getTenant(): Tenant
    {
        return auth()->user()->tenant;
    }

    public function isAdmin(): bool
    {
        // verifica se o email autenticado está listado
        // como admin no arquivo config/tenant.php
        return in_array(auth()->user()->email, config('tenant.admins'));
    }
}
