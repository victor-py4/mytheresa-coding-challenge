<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Domain\Contracts;

    use Illuminate\Pagination\Paginator;
    use Src\BoundedContext\Product\Domain\Product;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;

    interface ProductRepositoryContract
    {
        public function find(ProductId $id): ?Product;
        public function searchByParams(array $params): Paginator;
    }
