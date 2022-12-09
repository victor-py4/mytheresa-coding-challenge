<?php

namespace App\Http\Controllers\Discount;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Src\BoundedContext\Discount\Application\GetDiscountUseCase;

class DiscountController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Request            $request
     * @param GetDiscountUseCase $getDiscountUseCase
     *
     * @return JsonResponse
     */
    public function __invoke(
        Request            $request,
        GetDiscountUseCase $getDiscountUseCase
    ): JsonResponse
    {
        $discountId = $request->route('discountId');

        $responseResource = $getDiscountUseCase->execute((int)$discountId);

        return new JsonResponse($responseResource);
    }
}
