<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\SystemBase\app\Models\JsonViewResponse;

class ShoppingCartController extends Controller
{
    protected function getShoppingCartResponse(bool $forceReload = false)
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

    public function get(Request $request)
    {
        $jsonResponse = $this->getShoppingCartResponse();

        return $jsonResponse->go();
    }

    public function addProduct(Request $request)
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
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function removeProduct(Request $request)
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
