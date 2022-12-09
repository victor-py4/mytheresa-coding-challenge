<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\BoundedContext\Product\Application\GetProductUseCase;

/**
 * Class ProductController
 *
 * @package \App\Http\Controllers
 */
class ProductController extends Controller
{

    /**
     * @param \Illuminate\Http\Request                                  $request
     * @param \Src\BoundedContext\Product\Application\GetProductUseCase $getProductUseCase
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct(
        Request           $request,
        GetProductUseCase $getProductUseCase
    )
    {
        $productId = $request->route('productId');

        $responseResource = $getProductUseCase->execute((int)$productId);

        return new JsonResponse($responseResource);
    }

}

