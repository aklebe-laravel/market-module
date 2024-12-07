<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\SystemBase\app\Models\JsonViewResponse;

class RatingController extends Controller
{
    /**
     * @param  Request  $request
     * @param           $productId
     *
     * @return \Illuminate\Foundation\Application|Response|Application|ResponseFactory
     */
    public function showProduct(Request $request, $productId): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        /** @var JsonViewResponse $json */
        $json = app(JsonViewResponse::class);

        $view = view('forms.product-rating', [
            'formObjectId' => [
                'ratingProductId' => $productId,
                'ratingUserId'    => Auth::id(),
            ],
        ])->render();

        $json->setData([
            'html' => $view,
        ]);

        return $json->go();
    }

    /**
     * @param  Request  $request
     * @param           $targetUserId
     *
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function showUser(Request $request, $targetUserId): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        /** @var JsonViewResponse $json */
        $json = app(JsonViewResponse::class);

        $view = view('forms.user-rating', [
            'formObjectId' => [
                'ratingTargetUserId' => $targetUserId,
                'ratingSourceUserId' => Auth::id(),
            ],
        ])->render();

        $json->setData([
            'html' => $view,
        ]);

        return $json->go();
    }

}
