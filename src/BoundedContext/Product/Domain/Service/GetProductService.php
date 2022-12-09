<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Domain\Service;

use Src\BoundedContext\Product\Domain\Converter\ProductConverter;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository;

class GetProductService
{
    public function __construct(
        private EloquentProductRepository $productRepository,
        private GetProductPriceService    $getProductPriceService,
        private ProductConverter          $productConverter
    )
    {
    }

    public function getProduct(ProductId $productId): ProductResponseResource
    {
        $product = $this->productRepository->find($productId);

        $productPriceResponseResource = $this->getProductPriceService->getPrice($product);

        $productResponseResource = $this->productConverter->toResource($product);
        $productResponseResource->price = $productPriceResponseResource;

        return $productResponseResource;

    }

}
