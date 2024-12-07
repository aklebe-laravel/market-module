<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\SystemBase\app\Models\JsonViewResponse;

class ShoppingCartController extends Controller
{
    /**
     * @param  bool  $forceReload
     *
     * @return JsonViewResponse
     */
    protected function getShoppingCartResponse(bool $forceReload = false): JsonViewResponse
    {
        $jsonResponse = new JsonViewResponse('OK');
        $cart = app('market_settings')->getCurrentShoppingCart($forceReload);
        $responseData = [
            'qty'   => $cart->shoppingCartItems->count(), // force reload to be up-to-date with shoppingCartItems()
            'items' => $cart->shoppingCartItems,
        ];
        $jsonResponse->setData($responseData);

        return $jsonResponse;
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Foundation\Application|Response|Application|ResponseFactory
     */
    public function get(Request $request): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        $jsonResponse = $this->getShoppingCartResponse();

        return $jsonResponse->go();
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Foundation\Application|Response|Application|ResponseFactory
     */
    public function addProduct(Request $request): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        $cart = app('market_settings')->getCurrentShoppingCart();
        $cartItem = $cart->addProduct($request->get('product_id'));
        $jsonResponse = $this->getShoppingCartResponse(true);
        if (!$cartItem) {
            $jsonResponse->setErrorMessage('Product not found.');
        }

        return $jsonResponse->go();
    }

    /**
     * Used by categories and product detail view
     *
     * @param  Request  $request
     *
     * @return Application|ResponseFactory|Response
     */
    public function removeProduct(Request $request): Response|Application|ResponseFactory
    {
        $cart = app('market_settings')->getCurrentShoppingCart();
        $removed = $cart->removeProduct($request->get('product_id'));
        $jsonResponse = $this->getShoppingCartResponse(true);
        if (!$removed) {
            $jsonResponse->setErrorMessage('Product not found.');
        }

        return $jsonResponse->go();
    }
}
