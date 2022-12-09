<?php

declare(strict_types=1);

namespace Src\BoundedContext\Category\Domain;

use Src\BoundedContext\Category\Domain\ValueObjects\CategoryDiscountId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;

final class Category
{
    public function __construct(
        private CategoryId   $categoryId,
        private ?DiscountId  $discountId,
        private CategoryName $name,
    )
    {
    }


    public function discountId(): ?DiscountId
    {
        return $this->discountId;
    }

    public function id(): CategoryId
    {
        return $this->categoryId;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public static function create(
        CategoryId   $categoryId,
        ?DiscountId  $discountId,
        CategoryName $name,

    ): Category
    {

        $category = new self(
            $categoryId,
            $discountId,
            $name,
        );

        return $category;
    }
}
