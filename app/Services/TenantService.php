<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Support\Str;

class TenantService
{
    private $plan, $data = [];

    // public function __construct(Plan $plan, array $data)
    // {
    //     $this->plan = $plan;
    //     $this->data = $data;
    // }

    public function make(Plan $plan, array $data)
    {
        $this->plan = $plan;
        $this->data = $data;

        // cria o Tenant
        $tenant = $this->storeTenant();

        $user = $this->storeUser($tenant);

        return $user;
    }

    public function storeTenant()
    {
        $data = $this->data;

        return $this->plan->tenants()->create(
            [
                'cnpj' => $data['cnpj'],
                'name' => $data['empresa'],
                'email' => $data['email'],

                // data que se inscreveu
                'subscription' => now(),

                // quando expira o acesso (definimos para 5 dias depois da data atual)
                'expires_at' => now()->addDays(7),
            ]
        );
    }

    public function storeUser($tenant)
    {
        $data = $this->data;

        // cria o User
        $user = $tenant->users()->create(
            [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
            ]
        );

        return $user;
    }
}
