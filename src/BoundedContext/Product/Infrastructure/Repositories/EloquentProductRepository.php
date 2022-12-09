<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Product\Infrastructure\Repositories;

    use App\Models\Product as EloquentProductModel;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Pagination\Paginator;
    use Illuminate\Support\Facades\DB;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
    use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;
    use Src\BoundedContext\Product\Domain\Product;
    use Src\BoundedContext\Product\Domain\Contracts\ProductRepositoryContract;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductId;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductSku;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductName;
    use Src\BoundedContext\Product\Domain\ValueObjects\ProductPrice;


    class EloquentProductRepository implements ProductRepositoryContract
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

            if(is_null($product)) {
                throw new ModelNotFoundException(
                    "Product not found",
                    404
                );
            }

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

        /**
         * @param \Illuminate\Database\Query\Builder $query
         * @param string                             $categoryName
         *
         * @return \Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository
         */
        private function findByCategory(Builder $query, string $categoryName): self
        {
            $query
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('categories.name', '=', $categoryName);

            return $this;
        }

        /**
         * @param Builder $query
         * @param int     $price
         *
         * @return \Src\BoundedContext\Product\Infrastructure\Repositories\EloquentProductRepository
         */
        private function filterByPrice(Builder $query, int $price): self
        {
            $query
                ->where('products.price', '<=', $price);

            return $this;
        }

        /**
         * @param array $params
         *
         * @return \Illuminate\Database\Query\Builder|null
         */
        private function searchByParamsQuery(array $params): ?Builder
        {
            $query = DB::table('products')
                ->select('products.*');

            if(array_key_exists('category', $params) && !is_null($params["category"])) {
                $this->findByCategory($query, $params["category"]);
            }

            if(array_key_exists('priceFilter', $params) && !is_null($params["priceFilter"])) {
                $this->filterByPrice($query, (int)$params["priceFilter"]);
            }

            return $query;
        }

        /**
         * @param array $params
         *
         * @return Paginator
         */
        public function searchByParams(array $params): Paginator
        {
            $products = $this->searchByParamsQuery($params)->get()->all();

            return new Paginator($products, 5);
        }
    }
