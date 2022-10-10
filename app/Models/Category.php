<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = ['name', 'url', 'description'];
    
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
