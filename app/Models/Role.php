<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // permissoes que ainda não estão ligadas ao cargo
    public function permissionsAvailable($filter = null)
    {
        $permissions = Permission::whereNotIn(
            'permissions.id', function ($query) {
                $query->select('permission_role.permission_id');
                $query->from('permission_role');
                $query->whereRaw("permission_role.role_id={$this->id}");
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
