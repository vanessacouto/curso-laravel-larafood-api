<?php

namespace App\Listeners;

use App\Models\Role;
use App\Tenant\Events\TenantCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddRoleTenant
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TenantCreated $event)
    {
        $user = $event->user();

        // sempre adiciona a primeira role cadastrada no sistema
        if (!$role = Role::first()) {
            return;
        }

        // adiciona a role ao usuario recem criado
        $user->roles()->attach($role);
    }
}
