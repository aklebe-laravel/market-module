<?php

namespace Modules\Market\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\app\Http\Controllers\Controller;
use Modules\Market\app\Models\ShoppingCartItem;

class OfferController extends Controller
{
    public function potential(Request $request)
    {
        $cartItemsByUsers = [];
        $cart = app('market_settings')->getCurrentShoppingCart();
        /** @var ShoppingCartItem $shoppingCartItem */
        foreach ($cart->shoppingCartItems as $shoppingCartItem) {
            if (!isset($cartItemsByUsers[$shoppingCartItem->product->user_id])) {
                $cartItemsByUsers[$shoppingCartItem->product->user_id] = [];
            }
            $cartItemsByUsers[$shoppingCartItem->product->user_id]['items'][] = $shoppingCartItem->id;
            $cartItemsByUsers[$shoppingCartItem->product->user_id]['user'] = $shoppingCartItem->product->user;
        }

        return view('market::components.data-tables.tables.offers-potential', [
            'cartItemsByUsers' => $cartItemsByUsers,
        ]);
    }

}
