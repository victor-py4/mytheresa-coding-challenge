<?php

    declare(strict_types=1);

    namespace Src\BoundedContext\Category\Application;

    use Src\BoundedContext\Category\Domain\Contracts\CategoryRepositoryContract;
    use Src\BoundedContext\Category\Domain\Category;


    use Src\BoundedContext\Category\Domain\Converter\CategoryConverter;
    use Src\BoundedContext\Category\Domain\Resource\CategoryResponseResource;
    use Src\BoundedContext\Category\Domain\ValueObjects\CategoryId;

    final class GetCategoryUseCase {

        public function __construct(
            private CategoryRepositoryContract $repository,
            private CategoryConverter $categoryConverter
        ){
        }

        public function execute(
            int $categoryId,
        ): CategoryResponseResource
        {
            $id = new CategoryId($categoryId);

            $category = $this->repository->find($id);

            return $this->categoryConverter->toResource($category);

        }
    }
