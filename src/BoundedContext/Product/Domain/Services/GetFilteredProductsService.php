<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Services;

use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository;

class GetFilteredProductsService
{
    public function __construct(
        private EloquentProductRepository $productRepository,
        private ProductsConverter         $productsConverter,
        private GetProductService         $productService
    )
    {
    }

    /**
     * @param array $params
     *
     * @return ProductResponseResource[]
     */
    public function getProducts(array $params): array
    {
        $products = $this->productRepository->searchByParams($params);

        return array_map(
            fn($product) => $this->productService->getProduct(New ProductId($product->id)),
            $products->items()
        );
    }
}
