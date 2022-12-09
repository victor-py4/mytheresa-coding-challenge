<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Service;

use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
use Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository;

class GetFilteredProductsService
{
    public function __construct(
        private EloquentProductRepository $productRepository,
        private ProductsConverter         $productsConverter
    )
    {
    }

    public function getProducts(array $params): array
    {
        return $this->productRepository->searchByParams($params)->items();
    }
}
