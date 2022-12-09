<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Discount\Domain\ValueObjects;

    final class DiscountPercentage
    {
        /**
         * @var int
         */
        private $value;

        public function __construct(int $percentage)
        {
            $this->value = $percentage;
        }

        public function value(): int
        {
            return $this->value;
        }
    }
