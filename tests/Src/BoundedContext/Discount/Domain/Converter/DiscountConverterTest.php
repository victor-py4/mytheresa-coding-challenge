<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Discount\Domain\Converter;

use Src\BoundedContext\Discount\Domain\Converter\DiscountConverter;
use Src\BoundedContext\Discount\Domain\Discount;
use Src\BoundedContext\Discount\Domain\Resource\DiscountResponseResource;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountPercentage;
use Tests\TestCase;

final class DiscountConverterTest extends TestCase
{
    private DiscountConverter $sut;


    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new DiscountConverter();
    }

    public function test_when_a_given_discount_will_return_a_discount_respose_resource(): void
    {

        $discount = new Discount(
            new DiscountPercentage(20),
            new DiscountId(1)
        );

        $response = $this->sut->toResource($discount);

        $expectedResponse = new DiscountResponseResource();
        $expectedResponse->id = 1;
        $expectedResponse->percentage = 20;

        $this->assertEquals($expectedResponse, $response);

    }

}
