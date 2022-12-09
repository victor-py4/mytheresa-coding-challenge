<?php

declare(strict_types=1);

namespace Src\BoundedContext\Discount\Application;

use Src\BoundedContext\Discount\Domain\Contracts\DiscountRepositoryContract;
use Src\BoundedContext\Discount\Domain\Converter\DiscountConverter;
use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;

final class GetDiscountUseCase
{

    public function __construct(
        private DiscountRepositoryContract $repository,
        private DiscountConverter          $discountConverter
    )
    {
    }

    public function execute(
        int $discountId
    ): DiscountResponseResource
    {
        $id = new DiscountId($discountId);

        $discount = $this->repository->find($id);

        return $this->discountConverter->toResource($discount);
    }
}
