<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Application;

use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Product\Application\GetProductsUseCase;
use Src\BoundedContext\Product\Application\GetProductUseCase;
use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductsResponseResource;
use Src\BoundedContext\Product\Domain\Services\GetFilteredProductsService;
use Src\BoundedContext\Product\Domain\Services\GetProductService;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
use stdClass;
use Tests\TestCase;

final class GetProductsUseCaseTest extends TestCase
{
    private GetProductsUseCase $sut;
    private MockObject $getFilteredProductsService;
    private MockObject $productsConverter;

    public function setUp(): void
    {
        parent::setUp();

        $this->getFilteredProductsService = $this->createMock(GetFilteredProductsService::class);
        $this->productsConverter = $this->createMock(ProductsConverter::class);

        $this->sut = new GetProductsUseCase(
            $this->getFilteredProductsService,
            $this->productsConverter
        );
    }

    public function test_when_with_a_given_parameters_will_return_a_list_of_filtered_products(): void {

        $jacketCategory = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $firstProduct = new stdClass();
        $firstProduct->id = 1;
        $firstProduct->sku = '00001';
        $firstProduct->name = 'Blue leather jacket';
        $firstProduct->category_id = 1;
        $firstProduct->discount_id = null;
        $firstProduct->price = 90000;

        $secondProduct = new stdClass();
        $secondProduct->id = 2;
        $secondProduct->sku = '00002';
        $secondProduct->name = 'Hawaians yellow sandals';
        $secondProduct->category_id = 2;
        $secondProduct->discount_id = null;
        $secondProduct->price = 12000;

        $queryParams = [
            "category" => 'jackets',
            "priceFilter" => null,
        ];

        $productPriceResponseResource = new ProductPriceResponseResource();
        $productPriceResponseResource->original = $firstProduct->price;
        $productPriceResponseResource->final = $firstProduct->price;
        $productPriceResponseResource->discount_percentage = null;
        $productPriceResponseResource->currency = 'EUR';

        $productResponseResource = new ProductResponseResource();
        $productResponseResource->id = 1;
        $productResponseResource->sku = $firstProduct->sku;
        $productResponseResource->name = $firstProduct->name;
        $productResponseResource->category = $jacketCategory->name()->value();

        $productResponseResource->price = $productPriceResponseResource;

        $productsResponseResource = new ProductsResponseResource();
        $productsResponseResource->products = [$productResponseResource];

        $this->getFilteredProductsService
            ->expects(self::once())
            ->method('getProducts')
            ->with($queryParams)
            ->willReturn([$productResponseResource]);

        $this->productsConverter
            ->expects(self::once())
            ->method('toResource')
            ->with([$productResponseResource])
            ->willReturn($productsResponseResource);

        $request = new Request(
            [
                "category" => 'jackets',
                "priceLessThan" => null
            ]
        );

        $response = $this->sut->execute($request);

        $expectedProductPriceResponseResource = new ProductPriceResponseResource();
        $expectedProductPriceResponseResource->original = 90000;
        $expectedProductPriceResponseResource->final = 90000;
        $expectedProductPriceResponseResource->discount_percentage = null;
        $expectedProductPriceResponseResource->currency = 'EUR';

        $expectedProductResponseResource = new ProductResponseResource();
        $expectedProductResponseResource->id = 1;
        $expectedProductResponseResource->sku = '00001';
        $expectedProductResponseResource->name = 'Blue leather jacket';
        $expectedProductResponseResource->category = 'jackets';

        $expectedProductResponseResource->price = $expectedProductPriceResponseResource;

        $expectedProductsResponseResource = new ProductsResponseResource();
        $expectedProductsResponseResource->products = [$expectedProductResponseResource];

        $this->assertEquals($expectedProductsResponseResource, $response);

    }

}
