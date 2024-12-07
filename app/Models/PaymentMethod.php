<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\WebsiteBase\app\Models\Base\TraitBaseModel;

/**
 * @mixin IdeHelperPaymentMethod
 */
class PaymentMethod extends Model
{
    use HasFactory;
    use TraitBaseModel;

    protected $guarded = [];

    const string PAYMENT_METHOD_FREE = 'free';
    const string PAYMENT_METHOD_CASH = 'cash';
    const string PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    const string PAYMENT_METHOD_PREPAYMENT = 'prepayment';
    const string PAYMENT_METHOD_OFFER = 'offer';

    protected $table = 'payment_methods';

//    /**
//     * You can use this instead of newFactory()
//     * @var string
//     */
//    public static string $factory = PaymentMethodFactory::class;


}
