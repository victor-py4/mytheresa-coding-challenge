<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Converter;

use Src\BoundedContext\Category\Domain\Resource\CategoryResponseResource;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Infrastructure\Repositories\EloquentCategoryRepository;
use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Discount\Infrastructure\Repositories\EloquentDiscountRepository;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;

/**
 * Class CategoryConverter
 *
 * @package \Src\BoundedContext\Product\Domain\Converter
 */
class ProductConverter
{
    public function __construct(
        private EloquentCategoryRepository $eloquentCategoryRepository
    )
    {
    }

    public function toResource(
        Product $product
    ): ProductResponseResource
    {
        $productResponseResource = new ProductResponseResource();
        $productResponseResource->id = $product->id()->value();
        $productResponseResource->sku = $product->sku()->value();
        $productResponseResource->name = $product->name()->value();

        if(!is_null($product->categoryId())) {
            $categoryId = new CategoryId($product->categoryId()->value());
            $category =  $this->eloquentCategoryRepository->find($categoryId);
            $productResponseResource->category = $category->name()->value();
        }

        return $productResponseResource;
    }
}
