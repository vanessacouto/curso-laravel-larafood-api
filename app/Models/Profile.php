<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // relacionamento many to many com permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function plans() 
    {
        return $this->belongsToMany(Plan::class);
    }

    // permissoes que ainda não estão ligadas ao perfil
    public function permissionsAvailable($filter = null)
    {
        $permissions = Permission::whereNotIn(
            'permissions.id', function ($query) {
                $query->select('permission_profile.permission_id');
                $query->from('permission_profile');
                $query->whereRaw("permission_profile.profile_id={$this->id}");
            }
        )
            ->where(
                function ($queryFilter) use ($filter) {
                    if ($filter) { // só filtra se '$filter' possui valor
                        $queryFilter->where('permissions.name', 'LIKE', "%{$filter}%");
                    }
                }
            )
            ->paginate();

        return $permissions;
    }
}
