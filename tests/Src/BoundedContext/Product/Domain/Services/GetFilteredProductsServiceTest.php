<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Domain\Services;

use Illuminate\Pagination\Paginator;
use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductsResponseResource;
use Src\BoundedContext\Product\Domain\Services\GetFilteredProductsService;
use Src\BoundedContext\Product\Domain\Services\GetProductService;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository;
use stdClass;
use Tests\TestCase;

class GetFilteredProductsServiceTest extends TestCase
{
    private GetFilteredProductsService $sut;
    private MockObject $productRepository;
    private MockObject $productsConverter;
    private MockObject $getProductService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->createMock(EloquentProductRepository::class);
        $this->productsConverter = $this->createMock(ProductsConverter::class);
        $this->getProductService = $this->createMock(GetProductService::class);

        $this->sut = new GetFilteredProductsService(
            $this->productRepository,
            $this->productsConverter,
            $this->getProductService
        );
    }

    public function test_when_with_list_of_products_will_return_only_with_the_given_category(): void
    {
        $jacketCategory = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $sandalsCategory = new Category(
            new CategoryId(2),
            null,
            new CategoryName('sandals')
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
        ];

        $paginator = new Paginator([$firstProduct], 5);

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

        $this->productRepository
            ->expects(self::once())
            ->method('searchByParams')
            ->with($queryParams)
            ->willReturn($paginator);

        $this->getProductService
            ->expects(self::once())
            ->method('getProduct')
            ->with(new ProductId($firstProduct->id))
            ->willReturn($productResponseResource);

        $response = $this->sut->getProducts($queryParams);

        $expectedResponse = new ProductsResponseResource();

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


        $expectedResponse = [
            $expectedProductResponseResource
        ];

        $this->assertEquals($response, $expectedResponse);
    }
}
