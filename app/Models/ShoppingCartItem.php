<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Market\Models\IdeHelperShoppingCartItem;
use Modules\WebsiteBase\app\Models\Base\TraitBaseModel;


/**
 * @mixin IdeHelperShoppingCartItem
 */
class ShoppingCartItem extends Model
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
    protected $table = 'shopping_cart_items';

    protected $appends = [
        'price_formatted',
    ];

    /**
     * @return BelongsTo
     */
    public function shoppingCart()
    {
        return $this->belongsTo(ShoppingCart::class);
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * @return Attribute
     */
    protected function priceFormatted(): Attribute
    {
        return Attribute::make(get: function ($value, $attributes) {
            return app('system_base')->getPriceFormatted((float) $this->price, $this->currency_code,
                $this->paymentMethod->code);
        });
    }
}
