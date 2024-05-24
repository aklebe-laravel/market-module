<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Market\Models\IdeHelperPaymentMethod;
use Modules\WebsiteBase\app\Models\Base\TraitBaseModel;


/**
 * @mixin IdeHelperPaymentMethod
 */
class PaymentMethod extends Model
{
    use HasFactory;
    use TraitBaseModel;

    protected $guarded = [];

    const PAYMENT_METHOD_FREE = 'free';
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_METHOD_PREPAYMENT = 'prepayment';
    const PAYMENT_METHOD_OFFER = 'offer';

    protected $table = 'payment_methods';
}
