<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Resource;

class ProductPriceResponseResource
{
    public int $original;
    public int $final;
    public ?string $discount_percentage;
    public string $currency;

}
