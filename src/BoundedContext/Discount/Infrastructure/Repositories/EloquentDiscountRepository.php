<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Discount\Infrastructure\Repositories;

    use App\Models\Discount as EloquentDiscountModel;
    use Src\BoundedContext\Discount\Domain\Discount;
    use Src\BoundedContext\Discount\Domain\Contracts\DiscountRepositoryContract;
    use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
    use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountPercentage;

    class EloquentDiscountRepository implements DiscountRepositoryContract
    {
        /**
         * @var \App\Models\Discount
         */
        private $eloquentDiscountModel;

        public function __construct()
        {
            $this->eloquentDiscountModel = new EloquentDiscountModel;
        }

        public function find(DiscountId $id): ?Discount
        {
            $discount = $this->eloquentDiscountModel->findOrFail($id->value());

            return new Discount (
               new DiscountPercentage($discount->percentage),
               new DiscountId($id->value())
            );
        }
    }
