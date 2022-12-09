<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Domain\ValueObjects;

    final class ProductSku
    {
        /**
         * @var string
         */
        private $value;

        public function __construct(string $sku)
        {
            $this->value = $sku;
        }

        public function value(): string
        {
            return $this->value;
        }
    }
