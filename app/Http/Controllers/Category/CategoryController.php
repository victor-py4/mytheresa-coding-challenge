<?php

declare(strict_types=1);

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\BoundedContext\Category\Application\GetCategoryUseCase;

/**
 * Class CategoryController
 *
 * @package \App\Http\Controllers
 */
class CategoryController extends Controller
{

    /**
     * @param Request                                                     $request
     * @param GetCategoryUseCase $getCategoryUseCase
     *
     * @return JsonResponse
     */
    public function __invoke(
        Request            $request,
        GetCategoryUseCase $getCategoryUseCase
    ): JsonResponse
    {
        $discountId = $request->route('categoryId');

        $responseResource = $getCategoryUseCase->execute((int)$discountId);

        return new JsonResponse($responseResource);
    }

}

