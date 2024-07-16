<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\WebsiteBase\app\Models\Base\TraitBaseModel;

/**
 * @mixin IdeHelperShippingMethod
 */
class ShippingMethod extends Model
{
    use HasFactory;
    use TraitBaseModel;

    protected $guarded = [];

    const SHIPPING_METHOD_FREE = 'free';
    const SHIPPING_METHOD_DIGITAL = 'digital';
    const SHIPPING_METHOD_SELF_COLLECT = 'self_collect';
    const SHIPPING_METHOD_SELF_PRIVATE = 'private';

    protected $table = 'shipping_methods';
}
