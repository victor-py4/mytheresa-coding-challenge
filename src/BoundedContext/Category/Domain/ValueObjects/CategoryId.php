<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Category\Domain\ValueObjects;

    final class CategoryId
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
