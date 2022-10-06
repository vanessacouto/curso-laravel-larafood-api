<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

use App\Models\Traits\UserACLTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UserACLTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Scope a query to only authenticated users tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTenantUser(Builder $query)
    {
        // só vai trazer os usuarios cujo 'tenant_id' seja o mesmo 
        // do tenant do usuario autenticado
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }

    public function tenant() 
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // cargos que ainda não estão ligadas ao usuario
    public function rolesAvailable($filter = null)
    {
        $roles = Role::whereNotIn(
            'roles.id', function ($query) {
                $query->select('role_user.role_id');
                $query->from('role_user');
                $query->whereRaw("role_user.user_id={$this->id}");
            }
        )
            ->where(
                function ($queryFilter) use ($filter) {
                    if ($filter) { // só filtra se '$filter' possui valor
                        $queryFilter->where('roles.name', 'LIKE', "%{$filter}%");
                    }
                }
            )
            ->paginate();

        return $roles;
    }
}
