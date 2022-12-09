<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Category\Domain\ValueObjects;

    final class CategoryName
    {
        /**
         * @var string
         */
        private $value;

        public function __construct(string $name)
        {
            $this->value = $name;
        }

        public function value(): string
        {
            return $this->value;
        }
    }
