<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // relacionamento many to many com profiles
    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
