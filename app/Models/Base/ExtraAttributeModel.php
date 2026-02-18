<?php

namespace Modules\Market\app\Models\Base;

/**
 * For now just an accessor for attribute constants
 */
class ExtraAttributeModel extends \Modules\WebsiteBase\app\Models\Base\ExtraAttributeModel
{
    const string ATTR_PAYMENT_METHOD = 'payment_method';
    const string ATTR_SHIPPING_METHOD = 'shipping_method';
}