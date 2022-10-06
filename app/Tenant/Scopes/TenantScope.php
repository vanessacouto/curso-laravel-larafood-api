<?php

namespace App\Tenant\Scopes;

use App\Tenant\ManagerTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class TenantScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $identify = app(ManagerTenant::class)->getTenantIdentify();

        if ($identify) {
            // só vai trazer as categorias que tiverem o mesmo tenant_id do usuario logado
            $builder->where('tenant_id', $identify);
        }
    }
}
