<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Discount\Application;

use PHPUnit\Framework\MockObject\MockObject;
use Src\BoundedContext\Discount\Application\GetDiscountUseCase;
use Src\BoundedContext\Discount\Domain\Contracts\DiscountRepositoryContract;
use Src\BoundedContext\Discount\Domain\Converter\DiscountConverter;
use Src\BoundedContext\Discount\Domain\Discount;
use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountPercentage;
use Tests\TestCase;

final class GetDiscountUseCaseTest extends TestCase
{
    private GetDiscountUseCase $sut;
    private MockObject $repository;
    private MockObject $discountConverter;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(DiscountRepositoryContract::class);
        $this->discountConverter = $this->createMock(DiscountConverter::class);

        $this->sut = new GetDiscountUseCase(
            $this->repository,
            $this->discountConverter
        );
    }

    public function test_when_a_given_discount_id_will_return_a_discount(): void
    {

        $discount = new Discount(
            new DiscountPercentage(20),
            new DiscountId(1)
        );

        $discountResponseResource = new DiscountResponseResource();
        $discountResponseResource->id = $discount->discountId()->value();
        $discountResponseResource->percentage = $discount->percentage()->value();

        $this->repository
            ->expects(self::once())
            ->method('find')
            ->with(new DiscountId(1))
            ->willReturn($discount);

        $this->discountConverter
            ->expects(self::once())
            ->method('toResource')
            ->with($discount)
            ->willReturn($discountResponseResource);

        $response = $this->sut->execute(1);

        $expectedResponse = new DiscountResponseResource();
        $expectedResponse->id = 1;
        $expectedResponse->percentage = 20;

        $this->assertEquals($expectedResponse, $response);

    }

}
