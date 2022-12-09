<?php

declare(strict_types=1);

namespace Src\BoundedContext\Product\Application;

use Src\BoundedContext\Product\Domain\Contracts\ProductRepositoryContract;
use Src\BoundedContext\Product\Domain\Converter\ProductConverter;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Services\GetProductPriceService;
use Src\BoundedContext\Product\Domain\Services\GetProductService;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;

final class GetProductUseCase
{

    public function __construct(
        private ProductRepositoryContract $repository,
        private ProductConverter          $productConverter,
        private GetProductPriceService    $getProductPriceService,
        private GetProductService         $getProductService
    )
    {
        $this->repository = $repository;
    }

    public function execute(
        int $productId,

    ): ProductResponseResource
    {
        $id = new ProductId($productId);

        return $this->getProductService->getProduct($id);
    }
}
