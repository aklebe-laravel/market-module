<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\WebsiteBase\app\Models\Base\TraitBaseModel;

/**
 * @mixin IdeHelperShoppingCart
 */
class ShoppingCart extends Model
{
    use HasFactory;
    use TraitBaseModel;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'shopping_carts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shoppingCartItems()
    {
        return $this->hasMany(ShoppingCartItem::class);
    }

    /**
     * @param  int  $productId
     *
     * @return ShoppingCartItem|null
     */
    public function addProduct(int $productId): ?ShoppingCartItem
    {
        if (!$this->id) {
            Log::debug('Cart saved first time.', [__METHOD__]);
            $this->save();
        }

        /** @var Product $product */
        if ($product = Product::with([])->whereId($productId)->first()) {

            return ShoppingCartItem::create([
                'shopping_cart_id'   => $this->id,
                'product_id'         => $productId,
                'product_name'       => $product->name,
                'payment_method_id'  => $product->payment_method_id,
                'shipping_method_id' => $product->shipping_method_id,
                'price'              => $product->getExtraAttribute('price'),
                'currency_code'      => $product->getExtraAttribute('currency'),
            ]);

        } else {
            $errorMsg = 'Product not found :'.$productId;
            Log::error($errorMsg, [__METHOD__]);

            return null;
        }
    }

    /**
     * @param  int  $productId
     *
     * @return bool
     */
    public function removeProduct(int $productId): bool
    {
        if (!$this->id) {
            return false;
        }

        foreach ($this->shoppingCartItems as $shoppingCartItem) {
            if ($shoppingCartItem->product_id == $productId) {
                $shoppingCartItem->delete();

                return true;
            }
        }

        return false;
    }

    /**
     * @param  int  $itemId
     *
     * @return bool
     */
    public function removeItem(int $itemId): bool
    {
        if (!$this->id) {
            return false;
        }

        foreach ($this->shoppingCartItems as $shoppingCartItem) {
            if ($shoppingCartItem->id == $itemId) {
                $shoppingCartItem->delete();

                return true;
            }
        }

        return false;
    }

}
