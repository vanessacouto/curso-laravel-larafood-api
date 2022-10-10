<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function creating(Category $category)
    {
        // esse metodo é executado antes de criar o registro de fato
        $category->url = Str::kebab($category->name);

        // insere o uuid
        $category->uuid = Str::uuid();
    }

    /**
     * Handle the Category "updating" event.
     *
     * @param  \App\Models\Category $category
     * @return void
     */
    public function updating(Category $category)
    {
        // esse metodo é executado antes de atualizar o registro de fato
        $category->url = Str::kebab($category->name);
    }
}
