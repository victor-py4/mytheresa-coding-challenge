<?php

declare(strict_types=1);

namespace Src\BoundedContext\Discount\Domain;

use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountPercentage;

final class Discount {

    public function __construct(
      private DiscountPercentage $percentage,
      private DiscountId $discountId
    ){
    }

    public function percentage(): DiscountPercentage
    {
        return $this->percentage;
    }

    public function discountId(): DiscountId
    {
        return $this->discountId;
    }

    public static function create(
        DiscountPercentage $percentage,
        DiscountId $discountId,
    ): Discount
    {
        $discount = new self(
            $percentage,
            $discountId
        );

        return $discount;
    }
}
