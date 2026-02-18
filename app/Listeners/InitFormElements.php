<?php

namespace Modules\Market\app\Listeners;

use Modules\Form\app\Events\InitFormElements as InitFormElementsEvent;
use Modules\Form\app\Services\FormService;
use Modules\Market\app\Models\Base\ExtraAttributeModel;
use Modules\Market\app\Services\MarketFormService;

class InitFormElements
{
    /**
     * @param  InitFormElementsEvent  $event
     *
     * @return void
     */
    public function handle(InitFormElementsEvent $event): void
    {
        /** @var FormService $formService */
        $formService = app(FormService::class);
        /** @var MarketFormService $marketFormService */
        $marketFormService = app(MarketFormService::class);
        $formService->registerFormElement(ExtraAttributeModel::ATTR_PAYMENT_METHOD, fn($x) => $marketFormService::getFormElementPaymentMethod($x));
        $formService->registerFormElement(ExtraAttributeModel::ATTR_SHIPPING_METHOD, fn($x) => $marketFormService::getFormElementShippingMethod($x));
    }
}