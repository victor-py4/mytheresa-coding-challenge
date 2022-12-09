<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Category\Infrastructure\Repositories;

    use App\Models\Category as EloquentCategoryModel;
    use Src\BoundedContext\Category\Domain\Category;
    use Src\BoundedContext\Category\Domain\Contracts\CategoryRepositoryContract;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryDiscountId;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryName;
    use Src\BoundedContext\Discount\Domain\ValueObjects\DiscountId;


    final class EloquentCategoryRepository implements CategoryRepositoryContract
    {
        /**
         * @var \App\Models\Category
         */
        private $eloquentCategoryModel;

        public function __construct()
        {
            $this->eloquentCategoryModel = new EloquentCategoryModel;
        }

        public function find(CategoryId $id): ?Category
        {
            $category = $this->eloquentCategoryModel->findOrFail($id->value());

            $discountId = match ($category->discount_id) {
                null => null,
                default => new DiscountId($category->discount_id)
            };

            $category = new Category (
               $id,
               $discountId,
               new CategoryName($category->name),
            );

            return $category;
        }
    }
