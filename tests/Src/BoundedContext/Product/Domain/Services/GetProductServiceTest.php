<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Domain\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Product\Domain\Converter\ProductConverter;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Services\GetProductPriceService;
use Src\BoundedContext\Product\Domain\Services\GetProductService;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
use Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository;
use Tests\TestCase;

class GetProductServiceTest extends TestCase
{
    private GetProductService $sut;
    private MockObject $productRepository;
    private MockObject $getProductPriceService;
    private MockObject $productConverter;

    public function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->createMock(EloquentProductRepository::class);
        $this->getProductPriceService = $this->createMock(GetProductPriceService::class);
        $this->productConverter = $this->createMock(ProductConverter::class);

        $this->sut = new GetProductService(
            $this->productRepository,
            $this->getProductPriceService,
            $this->productConverter
        );
    }

    public function test_when_a_product_exist_will_return_a_successful_response(): void
    {
        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            new CategoryId(1),
            null,
            new ProductPrice(90000)
        );

        $category = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $productPriceResponseResource = new ProductPriceResponseResource();
        $productPriceResponseResource->original = 90000;
        $productPriceResponseResource->final = 90000;
        $productPriceResponseResource->discount_percentage = null;
        $productPriceResponseResource->currency = 'EUR';

        $productResponseResource = new ProductResponseResource();
        $productResponseResource->id = 1;
        $productResponseResource->sku = '00001';
        $productResponseResource->name = 'Blue leather jacket';
        $productResponseResource->category = 'jackets';

        $this->productRepository
            ->expects(self::once())
            ->method('find')
            ->with(new ProductId(1))
            ->willReturn($product);

        $this->getProductPriceService
            ->expects(self::once())
            ->method('getPrice')
            ->with($product)
            ->willReturn($productPriceResponseResource);

        $this->productConverter
            ->expects(self::once())
            ->method('toResource')
            ->with($product)
            ->willReturn($productResponseResource);

        $response = $this->sut->getProduct(new ProductId(1));

        $expectedResponse = clone $productResponseResource;
        $expectedResponse->price = $productPriceResponseResource;

        $this->assertEquals($response, $expectedResponse);
    }

    public function test_when_a_product_exist_will_return_a_failed_response(): void
    {
        $this->productRepository
            ->expects(self::once())
            ->method('find')
            ->with(new ProductId(2))
            ->willThrowException(new ModelNotFoundException(
                "Product not found",
                404
            ));

        $this->getProductPriceService
            ->expects(self::never())
            ->method('getPrice');

        $this->productConverter
            ->expects(self::never())
            ->method('toResource');

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("Product not found");

        $this->sut->getProduct(new ProductId(2));
    }
}
