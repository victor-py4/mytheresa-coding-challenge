<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Infrastructure\Repositories;

    use App\Models\Product as EloquentProductModel;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
    use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
    use Src\BoundedContext\Product\Domain\Product;
    use Src\BoundedContext\Product\Domain\Contracts\ProductRepositoryContract;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;


    final class EloquentProductRepository implements ProductRepositoryContract
    {
        /**
         * @var \App\Models\Product
         */
        private $eloquentProductModel;

        public function __construct()
        {
            $this->eloquentProductModel = new EloquentProductModel;
        }

        public function find(ProductId $id): ?Product
        {
            $product = $this->eloquentProductModel->findOrFail($id->value());

            $categoryId = match ($product->category_id) {
                null => null,
                default => new CategoryId($product->category_id)
            };

            $discountId = match ($product->discount_id) {
                null => null,
                default => new DiscountId($product->discount_id)
            };

            return new Product (
               new ProductId($product->id),
               new ProductSku($product->sku),
               new ProductName($product->name),
               $categoryId,
               $discountId,
               new ProductPrice($product->price),
            );
        }
    }
