<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\SystemBase\app\Models\JsonViewResponse;

class RatingController extends Controller
{
    public function showProduct(Request $request, $productId)
    {
        /** @var JsonViewResponse $json */
        $json = app(JsonViewResponse::class);

        $view = view('forms.product-rating', [
            'formObjectId' => [
                'ratingProductId' => $productId,
                'ratingUserId'    => Auth::id(),
            ]
        ])->render();

        $json->setData([
            'html' => $view,
        ]);

        return $json->go();
    }

    public function showUser(Request $request, $targetUserId)
    {
        /** @var JsonViewResponse $json */
        $json = app(JsonViewResponse::class);

        $view = view('forms.user-rating', [
            'formObjectId' => [
                'ratingTargetUserId' => $targetUserId,
                'ratingSourceUserId' => Auth::id(),
            ]
        ])->render();

        $json->setData([
            'html' => $view,
        ]);

        return $json->go();
    }

}
