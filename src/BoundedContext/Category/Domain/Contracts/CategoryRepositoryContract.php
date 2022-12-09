<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Category\Domain\Contracts;

    use Src\BoundedContext\Category\Domain\Category;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;

    interface CategoryRepositoryContract
    {
        public function find(CategoryId $id): ?Category;
    }
