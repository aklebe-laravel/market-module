<?php

namespace Modules\Market\app\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Market\app\Models\ShoppingCart;
use Modules\Market\app\Models\ShoppingCartItem;
use Modules\SystemBase\app\Services\Base\BaseService;

class ShoppingCartService extends BaseService
{
    /**
     * Cached cart of current user
     *
     * @var ShoppingCart|null
     */
    protected ?ShoppingCart $shoppingCart = null;

    /**
     * @param  bool      $forceReload
     * @param  int|null  $userId  if null, use Auth::user()
     *
     * @return ShoppingCart
     */
    public function getCurrentShoppingCart(bool $forceReload = false, ?int $userId = null): ShoppingCart
    {
        // cache current user only
        if (($userId === null && !$this->shoppingCart) || $forceReload) {
            $carts = ShoppingCart::with(['shoppingCartItems']);
            if ($userId === null) {
                if (Auth::check()) {
                    // get by user or by session
                    $carts->where('user_id', '=', Auth::id())->orWhere('session_token', '=', session()->getId());
                } else {
                    // get by session only
                    $carts->where('session_token', '=', session()->getId());
                }
            } else {
                $carts->where('user_id', '=', $userId);
            }

            if ($carts->count() > 0) {
                // @todo: merge carts here if needed

                // use the last one if multiple exists
                $this->shoppingCart = $carts->orderBy('updated_at', 'DESC')->first();
            } else {
                $this->shoppingCart = ShoppingCart::make([
                    'session_token' => session()->getId(),
                    'store_id'      => app('website_base_settings')->getStoreId(),
                    'user_id'       => Auth::id(),
                ]);
            }
        }

        return $this->shoppingCart;
    }

    /**
     * @param $cart1
     * @param $cart2
     *
     * @return void
     */
    protected function mergeCarts($cart1, $cart2)
    {
    }


    /**
     * @return array
     */
    public function getCartItemsGroupedByUsers(): array
    {
        $cartItemsByUsers = [];
        $cart = $this->getCurrentShoppingCart();
        /** @var ShoppingCartItem $shoppingCartItem */
        foreach ($cart->shoppingCartItems as $shoppingCartItem) {
            if (!isset($cartItemsByUsers[$shoppingCartItem->product->user_id])) {
                $cartItemsByUsers[$shoppingCartItem->product->user_id] = [];
            }
            $cartItemsByUsers[$shoppingCartItem->product->user_id]['items'][] = $shoppingCartItem->id;
            $cartItemsByUsers[$shoppingCartItem->product->user_id]['user'] = $shoppingCartItem->product->user;
        }

        return $cartItemsByUsers;
    }

}