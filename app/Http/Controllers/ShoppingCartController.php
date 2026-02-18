<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\Market\app\Services\ShoppingCartService;
use Modules\SystemBase\app\Models\JsonViewResponse;

class ShoppingCartController extends Controller
{
    /**
     * @param  Request  $request
     *
     * @return View|Factory|\Illuminate\Foundation\Application
     */
    public function show(Request $request): View|Factory|\Illuminate\Foundation\Application
    {
        $cartItemsByUser = app(ShoppingCartService::class)->getCartItemsGroupedByUsers();

        // If all items from one user only, we skip the "cart to potential" part and navigate the same way like \Modules\Market\app\Http\Controllers\OfferController::potential
        if (count($cartItemsByUser) === 1) {
            return view('market::components.data-tables.tables.offers-potential', [
                'cartItemsByUsers' => app(ShoppingCartService::class)->getCartItemsGroupedByUsers(),
            ]);
        }

        // Otherwise, show the cart grid first
        return view('website-base::page', [
            'title'         => 'Cart',
            'contentView'   => 'website-base::components.data-tables.tables.dt-simple',
            'livewireTable' => 'market::data-table.shopping-cart-item',
            'footerView'    => 'market::inc.shopping-cart.actions',
        ]);
    }

        /**
     * @param  bool  $forceReload
     *
     * @return JsonViewResponse
     */
    protected function getShoppingCartResponse(bool $forceReload = false): JsonViewResponse
    {
        $jsonResponse = new JsonViewResponse('OK');
        $cart = app(ShoppingCartService::class)->getCurrentShoppingCart($forceReload);
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
        $cart = app(ShoppingCartService::class)->getCurrentShoppingCart();
        $cartItem = $cart->addProduct($request->get('product_id'));
        $jsonResponse = $this->getShoppingCartResponse(true);
        if (!$cartItem) {
            $jsonResponse->setErrorMessage(__('Product not found.'));
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
        $cart = app(ShoppingCartService::class)->getCurrentShoppingCart();
        $removed = $cart->removeProduct($request->get('product_id'));
        $jsonResponse = $this->getShoppingCartResponse(true);
        if (!$removed) {
            $jsonResponse->setErrorMessage(__('Product not found.'));
        }

        return $jsonResponse->go();
    }
}
