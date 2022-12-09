<?php

namespace Src\BoundedContext\Category\Domain\Converter;

use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\Resource\CategoryResponseResource;
use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Discount\Infrastructure\Repositories\EloquentDiscountRepository;

/**
 * Class CategoryConverter
 *
 * @package \Src\BoundedContext\Category\Domain\Converter
 */
class CategoryConverter
{
    public function __construct(
        private EloquentDiscountRepository $eloquentDiscountRepository
    )
    {
    }

    public function toResource(
        Category $category
    ): CategoryResponseResource
    {
        $categoryResponseResource = new CategoryResponseResource();
        $categoryResponseResource->id = $category->id()->value();
        $categoryResponseResource->name = $category->name()->value();

        if (!is_null($category->discountId())) {
            $discountId = new DiscountId($category->discountId()->value());
            $discount = $this->eloquentDiscountRepository->find($discountId);
            $discountResponseResource = new DiscountResponseResource();
            $discountResponseResource->discountId = $discount->discountId()->value();
            $discountResponseResource->percentage = $discount->percentage()->value();
            $categoryResponseResource->discount = $discountResponseResource;
        }

        return $categoryResponseResource;
    }
}
