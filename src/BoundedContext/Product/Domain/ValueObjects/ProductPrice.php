<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Domain\ValueObjects;

    final class ProductPrice
    {
        public const CURRENCY = 'EUR';

        /**
         * @var int
         */
        private $value;

        public function __construct(int $Price)
        {
            $this->value = $Price;
        }

        public function value(): int
        {
            return $this->value;
        }
    }
