<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Domain\Services;

use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Category\Infrastructure\Repositories\EloquentCategoryRepository;
use Src\BoundedContext\Discount\Domain\Discount;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountPercentage;
use Src\BoundedContext\Discount\Infrastructure\Repositories\EloquentDiscountRepository;
use Src\BoundedContext\Product\Domain\Product;
use Src\BoundedContext\Product\Domain\Resource\ProductPriceResponseResource;
use Src\BoundedContext\Product\Domain\Services\GetProductPriceService;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;
use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
use Tests\TestCase;

class GetProductPriceServiceTest extends TestCase
{
    private GetProductPriceService $sut;
    private MockObject $categoryRepository;
    private MockObject $discountRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoryRepository = $this->createMock(EloquentCategoryRepository::class);
        $this->discountRepository = $this->createMock(EloquentDiscountRepository::class);

        $this->sut = new GetProductPriceService(
            $this->categoryRepository,
            $this->discountRepository,
        );
    }

    public function test_when_product_and_product_category_has_no_discounts_will_return_price_without_discount(): void
    {
        $category = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            new CategoryId(1),
            null,
            new ProductPrice(90000)
        );

        $this->categoryRepository
            ->expects(self::once())
            ->method('find')
            ->with($product->categoryId())
            ->willReturn($category);

        $this->discountRepository
            ->expects(self::never())
            ->method('find');

        $response = $this->sut->getPrice($product);

        $expectedResponse = new ProductPriceResponseResource();
        $expectedResponse->original = 90000;
        $expectedResponse->final = 90000;
        $expectedResponse->discount_percentage = null;
        $expectedResponse->currency = 'EUR';

        $this->assertEquals($response, $expectedResponse);
    }

    public function test_when_product_has_no_discount_and_product_category_has_will_return_price_with_discount(): void
    {
        $discount = new Discount(
            new DiscountPercentage(50),
            new DiscountId(12)
        );

        $category = new Category(
            new CategoryId(1),
            new DiscountId(12),
            new CategoryName('jackets')
        );

        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            new CategoryId(1),
            null,
            new ProductPrice(90000)
        );

        $this->categoryRepository
            ->expects(self::once())
            ->method('find')
            ->with($product->categoryId())
            ->willReturn($category);

        $this->discountRepository
            ->expects(self::once())
            ->method('find')
            ->with($category->discountId())
            ->willReturn($discount);

        $response = $this->sut->getPrice($product);

        $expectedResponse = new ProductPriceResponseResource();
        $expectedResponse->original = 90000;
        $expectedResponse->final = 45000;
        $expectedResponse->discount_percentage = '50%';
        $expectedResponse->currency = 'EUR';

        $this->assertEquals($response, $expectedResponse);
    }

    public function test_when_product_has_discount_and_product_category_has_not_will_return_price_with_discount(): void
    {
        $discount = new Discount(
            new DiscountPercentage(50),
            new DiscountId(12)
        );

        $category = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            new CategoryId(1),
            new DiscountId(12),
            new ProductPrice(90000)
        );

        $this->categoryRepository
            ->expects(self::once())
            ->method('find')
            ->with($product->categoryId())
            ->willReturn($category);

        $this->discountRepository
            ->expects(self::once())
            ->method('find')
            ->with($product->discountId())
            ->willReturn($discount);

        $response = $this->sut->getPrice($product);

        $expectedResponse = new ProductPriceResponseResource();
        $expectedResponse->original = 90000;
        $expectedResponse->final = 45000;
        $expectedResponse->discount_percentage = '50%';
        $expectedResponse->currency = 'EUR';

        $this->assertEquals($response, $expectedResponse);
    }

    public function test_when_product_has_discount_and_product_category_too_will_return_price_with_the_highest_discount(): void
    {
        $categoryDiscount = new Discount(
            new DiscountPercentage(20),
            new DiscountId(12)
        );

        $productDiscount = new Discount(
            new DiscountPercentage(50),
            new DiscountId(13)
        );

        $category = new Category(
            new CategoryId(1),
            new DiscountId(12),
            new CategoryName('jackets')
        );

        $product = new Product(
            new ProductId(1),
            new ProductSku('00001'),
            new ProductName('Blue leather jacket'),
            new CategoryId(1),
            new DiscountId(13),
            new ProductPrice(90000)
        );

        $this->categoryRepository
            ->expects(self::once())
            ->method('find')
            ->with($product->categoryId())
            ->willReturn($category);

        $this->discountRepository
            ->expects(self::exactly(2))
            ->method('find')
            ->withConsecutive([$category->discountId()],[$product->discountId()])
            ->willReturnOnConsecutiveCalls($categoryDiscount, $productDiscount);

        $response = $this->sut->getPrice($product);

        $expectedResponse = new ProductPriceResponseResource();
        $expectedResponse->original = 90000;
        $expectedResponse->final = 45000;
        $expectedResponse->discount_percentage = '50%';
        $expectedResponse->currency = 'EUR';

        $this->assertEquals($response, $expectedResponse);
    }
}
