<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Application;

    use Illuminate\Http\Request;
    use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
    use Src\BoundedContext\Product\Domain\Resource\ProductsResponseResource;
    use Src\BoundedContext\Product\Domain\Services\GetFilteredProductsService;

    final class GetProductsUseCase {

        public function __construct(
            private GetFilteredProductsService $getFilteredProductsService,
            private ProductsConverter $productsConverter,
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

           $productsResponse = $this->getFilteredProductsService->getProducts($queryParams);

           return $this->productsConverter->toResource($productsResponse);
        }
    }
