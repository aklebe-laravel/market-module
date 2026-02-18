<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    //    /**
    //     * You can use this instead of newFactory()
    //     * @var string
    //     */
    //    public static string $factory = ShoppingCartFactory::class;

    /**
     * @return HasMany
     */
    public function shoppingCartItems(): HasMany
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
        if (!$this->getKey()) {
            Log::debug('Cart saved first time.', [__METHOD__]);
            $this->save();
        }

        /** @var Product $product */
        if ($product = Product::with([])->whereId($productId)->first()) {

            return ShoppingCartItem::create([
                'shopping_cart_id'   => $this->getKey(),
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
     * Remove a product from cart.
     *
     * @param  int  $productId
     *
     * @return bool
     */
    public function removeProduct(int $productId): bool
    {
        if (!$this->getKey()) {
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
     * Remove all items from cart.
     *
     * @return bool
     */
    public function removeItems(): bool
    {
        if (!$this->getKey()) {
            return false;
        }
        Log::debug('Shopping cart items: '.$this->shoppingCartItems->count());
        foreach ($this->shoppingCartItems as $shoppingCartItem) {
            $shoppingCartItem->delete();
            Log::debug('Removed item from shopping cart :'.$shoppingCartItem->getKey());
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
        if (!$this->getKey()) {
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
