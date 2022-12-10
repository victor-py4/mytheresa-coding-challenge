<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Domain\Converter;

use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Infrastructure\Repositories\EloquentCategoryRepository;
use Src\BoundedContext\Product\Domain\Converter\ProductConverter;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
use Tests\TestCase;

class ProductConverterTest extends TestCase
{
    private ProductConverter $sut;
    private MockObject $eloquentCategoryRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentCategoryRepository = $this->createMock(EloquentCategoryRepository::class);

        $this->sut = new ProductConverter(
            $this->eloquentCategoryRepository
        );
    }

    public function test_when_a_given_product_will_return_a_product_response_resource(): void
    {
        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            null,
            null,
            new ProductPrice(90000)
        );

        $this->eloquentCategoryRepository
            ->expects(self::never())
            ->method('find');

        $productResponseResource = new ProductResponseResource();
        $productResponseResource->id = 1;
        $productResponseResource->sku = '00001';
        $productResponseResource->name = 'Blue leather jacket';

        $response = $this->sut->toResource($product);

        $this->assertEquals($productResponseResource, $response);
    }
}
