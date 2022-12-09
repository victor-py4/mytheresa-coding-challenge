<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Services;

use Src\BoundedContext\Category\Infrastructure\Repositories\EloquentCategoryRepository;
use Src\BoundedContext\Discount\Infrastructure\Repositories\EloquentDiscountRepository;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;

class GetProductPriceService
{
    public function __construct(
        private EloquentCategoryRepository $categoryRepository,
        private EloquentDiscountRepository $discountRepository
    )
    {
    }

    public function getPrice(Product $product): ProductPriceResponseResource
    {
        $discounts = [];

        if (!is_null($product->categoryId())) {
            $category = $this->categoryRepository->find($product->categoryId());

            if (!is_null($category->discountId())) {
                $discounts[] = $this->discountRepository->find($category->discountId())->percentage()->value();
            }
        }

        if (!is_null($product->discountId())) {
            $discounts[] = $this->discountRepository->find($product->discountId())->percentage()->value();
        }

        if (empty($discounts)) {
            $productPriceResponseResource = new ProductPriceResponseResource();
            $productPriceResponseResource->original = $product->price()->value();
            $productPriceResponseResource->final = $product->price()->value();
            $productPriceResponseResource->discount_percentage = null;
            $productPriceResponseResource->currency = ProductPrice::CURRENCY;
        } else {
            $discount = max($discounts);
            $productPriceResponseResource = new ProductPriceResponseResource();
            $productPriceResponseResource->original = $product->price()->value();
            $productPriceResponseResource->final = $this->applyPriceDiscount($product->price()->value(), $discount);
            $productPriceResponseResource->discount_percentage = "{$discount}%";
            $productPriceResponseResource->currency = ProductPrice::CURRENCY;
        }

        return $productPriceResponseResource;

    }

    /**
     * @param int $price
     * @param int $discount
     *
     * @return float|int
     */
    private function applyPriceDiscount(int $price, int $discount)
    {
        return ($price * (100 - $discount)) / 100;
    }
}
