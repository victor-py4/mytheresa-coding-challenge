<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Application;

    use Illuminate\Http\Request;
    use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
    use Src\BoundedContext\Product\Domain\Resource\ProductsResponseResource;
    use Src\BoundedContext\Product\Domain\Service\GetFilteredProductsService;
    use Src\BoundedContext\Product\Domain\Service\GetProductService;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;

    final class GetProductsUseCase {

        public function __construct(
            private GetFilteredProductsService $getFilteredProductsService,
            private ProductsConverter $productsConverter,
            private GetProductService $productService
        )
        {
        }

        public function execute(
            Request $request,

        ): ProductsResponseResource
        {
           $queryParams = [
               "category" => $request->query('category'),
               "priceFilter" => $request->query('priceLessThan')
           ];

           $products = $this->getFilteredProductsService->getProducts($queryParams);

           $productsResponse = array_map(
               fn($product) => $this->productService->getProduct(new ProductId($product->id)),
               $products
           );

           return $this->productsConverter->toResource($productsResponse);
        }
    }
