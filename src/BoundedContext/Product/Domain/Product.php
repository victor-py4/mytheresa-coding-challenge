<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain;

use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductCategoryId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductDiscountId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;

final class Product
{


    public function __construct(
        private ProductId          $id,
        private ProductSku         $sku,
        private ProductName        $name,
        private ?CategoryId $categoryId,
        private ?DiscountId $discountId,
        private ProductPrice       $price,
    )
    {
    }

    public function categoryId(): ?CategoryId
    {
        return $this->categoryId;
    }

    public function discountId(): ?DiscountId
    {
        return $this->discountId;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function sku(): ProductSku
    {
        return $this->sku;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public static function create(
        ProductId         $id,
        ProductSku        $sku,
        ProductName       $name,
        CategoryId $category_id,
        DiscountId $discount_id,
        ProductPrice      $price,

    ): Product
    {

        $product = new self(
            $id,
            $sku,
            $name,
            $category_id,
            $discount_id,
            $price,
        );

        return $product;
    }
}
