<?php

namespace Src\BoundedContext\Category\Domain\Resource;

use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;

class CategoryResponseResource
{
    public int $id;
    public string $name;
    public ?DiscountResponseResource $discount;
    public ?string $createdAt;
    public ?string $updatedAt;

}
