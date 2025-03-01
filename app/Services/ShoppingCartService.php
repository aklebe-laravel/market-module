<?php

namespace Modules\Market\app\Services;

use Modules\Market\app\Models\ShoppingCart;
use Modules\SystemBase\app\Services\Base\BaseService;

class ShoppingCartService extends BaseService
{
    /**
     * @param  int|null  $userId
     *
     * @return ShoppingCart|null
     */
    public function getCurrentShoppingCart(?int $userId): ?ShoppingCart
    {
        $carts = ShoppingCart::with(['shoppingCartItems'])->where('user_id', '=', $userId);
        if ($carts->get() && $carts->count() > 0) {
            return $carts->orderBy('updated_at', 'DESC')->first();
        }

        return null;
    }

}