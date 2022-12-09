<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Application;

    use Src\BoundedContext\Product\Domain\Contracts\ProductRepositoryContract;
    use Src\BoundedContext\Product\Domain\Converter\ProductConverter;


    use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
    use Src\BoundedContext\Product\Domain\Service\GetProductPriceService;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;

    final class GetProductUseCase {

        public function __construct(
            private ProductRepositoryContract $repository,
            private ProductConverter $productConverter,
            private GetProductPriceService $getProductPriceService
        )
        {
            $this->repository = $repository;
        }

        public function execute(
            int $productId,

        ): ProductResponseResource
        {
            $id = new ProductId($productId);

           $product = $this->repository->find($id);

           $productPriceResponseResource = $this->getProductPriceService->getPrice($product);

           $productResponseResource = $this->productConverter->toResource($product);
           $productResponseResource->price = $productPriceResponseResource;

           return $productResponseResource;
        }
    }
