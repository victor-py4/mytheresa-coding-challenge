<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Application;

use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Product\Application\GetProductUseCase;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Services\GetProductService;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
use Tests\TestCase;

final class GetProductUseCaseTest extends TestCase
{
    private GetProductUseCase $sut;
    private MockObject $getProductService;

    public function setUp(): void
    {
        parent::setUp();

        $this->getProductService = $this->createMock(GetProductService::class);

        $this->sut = new GetProductUseCase(
            $this->getProductService
        );
    }

    public function test_when_given_a_product_id_will_return_a_product_response_resource(): void {

        $category = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            $category->id(),
            null,
            new ProductPrice(90000)
        );

        $productPriceResponseResource = new ProductPriceResponseResource();
        $productPriceResponseResource->original = $product->price()->value();
        $productPriceResponseResource->final = $product->price()->value();
        $productPriceResponseResource->discount_percentage = null;
        $productPriceResponseResource->currency = 'EUR';

        $productResponseResource = new ProductResponseResource();
        $productResponseResource->id = 1;
        $productResponseResource->sku = $product->sku()->value();
        $productResponseResource->name = $product->name()->value();
        $productResponseResource->category = $category->name()->value();
        $productResponseResource->price = $productPriceResponseResource;

        $this->getProductService
            ->expects(self::once())
            ->method('getProduct')
            ->with($product->id())
            ->willReturn($productResponseResource);

        $response = $this->sut->execute(1);

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

        $this->assertEquals($response, $expectedProductResponseResource);
    }

}
