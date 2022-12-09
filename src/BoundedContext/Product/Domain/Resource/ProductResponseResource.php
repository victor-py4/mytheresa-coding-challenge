<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Resource;

use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;

class ProductResponseResource
{
    public int $id;
    public string $sku;
    public string $name;
    public string $category;
    public ProductPriceResponseResource $price;
    public ?string $createdAt;
    public ?string $updatedAt;

}
