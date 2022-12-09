<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Discount\Domain\ValueObjects;

    final class DiscountId
    {
        /**
         * @var int
         */
        private $value;

        public function __construct(int $id)
        {
            $this->value = $id;
        }

        public function value(): int
        {
            return $this->value;
        }
    }
