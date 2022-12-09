<?php

namespace Src\BoundedContext\Discount\Domain\Resource;

/**
 * Class DiscountResponseResource
 *
 * @package \Src\BoundedContext\Discount\Domain\Resource
 */
class DiscountResponseResource
{
    public int $id;
    public int $percentage;
    public ?string $createdAt;
    public ?string $updatedAt;
}
