<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Category\Application;

use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Category\Application\GetCategoryUseCase;
use Src\BoundedContext\Category\Domain\Category;
use Src\BoundedContext\Category\Domain\Contracts\CategoryRepositoryContract;
use Src\BoundedContext\Category\Domain\Converter\CategoryConverter;
use Src\BoundedContext\Category\Domain\Resource\CategoryResponseResource;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
use Tests\TestCase;

final class GetCategoryUseCaseTest extends TestCase
{
    private GetCategoryUseCase $sut;
    private MockObject $repository;
    private MockObject $categoryConverter;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CategoryRepositoryContract::class);
        $this->categoryConverter = $this->createMock(CategoryConverter::class);

        $this->sut = new GetCategoryUseCase(
            $this->repository,
            $this->categoryConverter
        );
    }

    public function test_when_a_given_category_id_will_return_a_category(): void
    {

        $category = new Category(
            new CategoryId(1),
            null,
            new CategoryName('jackets')
        );

        $categoryResponseResource = new CategoryResponseResource();
        $categoryResponseResource->id = $category->id()->value();
        $categoryResponseResource->name = $category->name()->value();

        $this->repository
            ->expects(self::once())
            ->method('find')
            ->with(new CategoryId(1))
            ->willReturn($category);

        $this->categoryConverter
            ->expects(self::once())
            ->method('toResource')
            ->with($category)
            ->willReturn($categoryResponseResource);

        $response = $this->sut->execute(1);

        $expectedResponse = new CategoryResponseResource();
        $expectedResponse->id = 1;
        $expectedResponse->name = 'jackets';

        $this->assertEquals($expectedResponse, $response);

    }

}
