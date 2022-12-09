<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\BoundedContext\Category\Domain\Contracts\CategoryRepositoryContract;
use Src\BoundedContext\Category\Infrastructure\Repositories\EloquentCategoryRepository;
use Src\BoundedContext\Discount\Domain\Contracts\DiscountRepositoryContract;
use Src\BoundedContext\Discount\Infrastructure\Repositories\EloquentDiscountRepository;
use Src\BoundedContext\Product\Domain\Contracts\ProductRepositoryContract;
use Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        DiscountRepositoryContract::class => EloquentDiscountRepository::class,
        CategoryRepositoryContract::class => EloquentCategoryRepository::class,
        ProductRepositoryContract::class => EloquentProductRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
