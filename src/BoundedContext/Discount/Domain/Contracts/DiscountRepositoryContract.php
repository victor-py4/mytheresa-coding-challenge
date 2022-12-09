<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Discount\Domain\Contracts;

    use Src\BoundedContext\Discount\Domain\Discount;
    use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;

    interface DiscountRepositoryContract
    {
        public function find(DiscountId $id): ?Discount;
    }
