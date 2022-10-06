<?php

namespace App\Models;

use App\Models\Category;
use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    // para só exibir os produtos do Tenant
    use TenantTrait;

    protected $fillable = ['title', 'flag', 'price', 'description', 'image'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // categorias que ainda não estão ligadas ao produto
    public function categoriesAvailable($filter = null)
    {
        $categories = Category::whereNotIn(
            'categories.id', function ($query) {
                $query->select('category_product.category_id');
                $query->from('category_product');
                $query->whereRaw("category_product.product_id={$this->id}");
            }
        )
            ->where(
                function ($queryFilter) use ($filter) {
                    if ($filter) { // só filtra se '$filter' possui valor
                        $queryFilter->where('categories.name', 'LIKE', "%{$filter}%");
                    }
                }
            )
            ->paginate();

        return $categories;
    }
}
