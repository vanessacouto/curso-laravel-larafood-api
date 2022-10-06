<?php

namespace App\Providers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Category;
use App\Observers\PlanObserver;
use App\Observers\TenantObserver;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Plan::observe(PlanObserver::class);
        Tenant::observe(TenantObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
    }
}
