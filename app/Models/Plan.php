<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'price', 'description'];

    public function details() 
    {
        return $this->hasMany(DetailPlan::class);
    }

    public function profiles() 
    {
        return $this->belongsToMany(Profile::class);
    }

    public function tenants() 
    {
        return $this->hasMany(Tenant::class);
    }

    public function search($filter = null) 
    {
        $results = $this
                    ->where('name', 'LIKE', "%{$filter}%")
                    ->orWhere('description', 'LIKE', "%{$filter}%")
                    ->paginate();

        return $results;
    }

    // perfis que ainda não estão ligadas ao plano
    public function profilesAvailable($filter = null)
    {
        $profiles = Profile::whereNotIn(
            'profiles.id', function ($query) {
                $query->select('plan_profile.profile_id');
                $query->from('plan_profile');
                $query->whereRaw("plan_profile.plan_id={$this->id}");
            }
        )
            ->where(
                function ($queryFilter) use ($filter) {
                    if ($filter) { // só filtra se '$filter' possui valor
                        $queryFilter->where('profiles.name', 'LIKE', "%{$filter}%");
                    }
                }
            )
            ->paginate();

        return $profiles;
    }
}