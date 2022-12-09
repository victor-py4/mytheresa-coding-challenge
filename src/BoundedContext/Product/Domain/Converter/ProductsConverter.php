<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Converter;

use Src\BoundedContext\Category\Infrastructure\Repositories\EloquentCategoryRepository;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductsResponseResource;

/**
 * Class CategoryConverter
 *
 * @package \Src\BoundedContext\Product\Domain\Converter
 */
class ProductsConverter
{
    public function __construct(
        private EloquentCategoryRepository $eloquentCategoryRepository
    )
    {
    }

    /**
     * @param ProductResponseResource[] $productsResponse
     *
     * @return ProductsResponseResource
     */
    public function toResource(
        array $productsResponse
    ): ProductsResponseResource
    {
       $productsResponseResource = new ProductsResponseResource();
       $productsResponseResource->products = $productsResponse;

        return $productsResponseResource;
    }
}
