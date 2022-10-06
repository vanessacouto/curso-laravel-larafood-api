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
use App\Repositories\TenantRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\TenantRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            TenantRepositoryInterface::class,
            TenantRepository::class
        );
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
