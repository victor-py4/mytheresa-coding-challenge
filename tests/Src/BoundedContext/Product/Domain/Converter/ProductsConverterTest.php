<?php

declare(strict_types=1);

namespace Tests\Src\BoundedContext\Product\Domain\Converter;

use Src\BoundedContext\Product\Domain\Converter\ProductsConverter;
use Src\BoundedContext\Product\Domain\Resource\ProductResponseResource;
use Src\BoundedContext\Product\Domain\Resource\ProductsResponseResource;
use Tests\TestCase;

class ProductsConverterTest extends TestCase
{
    private ProductsConverter $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ProductsConverter();
    }

    public function test_when_a_given_array_of_product_respose_resource_will_return_a_products_response_resource(): void
    {
        $productResponseResource = new ProductResponseResource();
        $productResponseResource->id = 1;
        $productResponseResource->sku = '00001';
        $productResponseResource->name = 'Blue leather jacket';

        $response = $this->sut->toResource([$productResponseResource]);

        $productsResponseResource = new ProductsResponseResource();
        $productsResponseResource->products = [$productResponseResource];

        $this->assertEquals($productsResponseResource, $response);
    }
}
