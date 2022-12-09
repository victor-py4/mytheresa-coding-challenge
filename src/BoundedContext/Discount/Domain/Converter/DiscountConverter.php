<?php

namespace Src\BoundedContext\Discount\Domain\Converter;

use Src\BoundedContext\Discount\Domain\Discount;
use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;

/**
 * Class DiscountConverter
 *
 * @package \Src\BoundedContext\Discount\Domain\Converter
 */
class DiscountConverter
{
    public function toResource(
        Discount $discount
    ): DiscountResponseResource
    {
        $responseResource = new DiscountResponseResource();
        $responseResource->id = $discount->discountId()->value();
        $responseResource->percentage = $discount->percentage()->value();

        return $responseResource;
    }
}
