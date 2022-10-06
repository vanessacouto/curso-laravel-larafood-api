<?php

namespace App\Tenant\Traits;

use App\Tenant\Observers\TenantObserver;
use App\Tenant\Scopes\TenantScope;

trait TenantTrait
{
    // reescrevendo o metodo booted
    // ao inserir uma nova categoria, já vai inserir o id do tenant do usuario logado
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::booted();
        static::observe(TenantObserver::class);

        // só vai trazer as categorias que tiverem o mesmo tenant_id do usuario logado
        static::addGlobalScope(new TenantScope);
    }
}