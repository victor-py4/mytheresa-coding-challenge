<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Category\Domain\Converter;

use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\Converter\CategoryConverter;
use Src\BoundedContext\Category\Domain\Resource\CategoryResponseResource;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Src\BoundedContext\Discount\Infrastructure\Repositories\EloquentDiscountRepository;
use Tests\TestCase;

final class CategoryConverterTest extends TestCase
{
    private CategoryConverter $sut;
    private MockObject $eloquentDiscountRepository;


    public function setUp(): void
    {
        parent::setUp();

        $this->eloquentDiscountRepository = $this->createMock(EloquentDiscountRepository::class);

        $this->sut = new CategoryConverter(
            $this->eloquentDiscountRepository
        );
    }

    public function test_when_a_given_category_will_return_a_category_respose_resource(): void
    {

        $category = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $this->eloquentDiscountRepository
            ->expects(self::never())
            ->method('find');

        $response = $this->sut->toResource($category);

        $expectedResponse = new CategoryResponseResource();
        $expectedResponse->id = 1;
        $expectedResponse->name = 'jackets';

        $this->assertEquals($expectedResponse, $response);

    }

}
