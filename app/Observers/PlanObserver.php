<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Plan;

class PlanObserver
{
    /**
     * Handle the Plan "creating" event.
     *
     * @param  \App\Models\Plan  $plan
     * @return void
     */
    public function creating(Plan $plan)
    {
        // esse metodo é executado antes de criar o registro de fato
        $plan->url = Str::kebab($plan->name);
    }

    /**
     * Handle the Plan "updating" event.
     *
     * @param  \App\Models\Plan  $plan
     * @return void
     */
    public function updating(Plan $plan)
    {
        // esse metodo é executado antes de atualizar o registro de fato
        $plan->url = Str::kebab($plan->name);
    }
}
