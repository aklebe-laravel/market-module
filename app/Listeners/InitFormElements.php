<?php

namespace Modules\Market\app\Listeners;

use Modules\Form\app\Events\InitFormElements as InitFormElementsEvent;
use Modules\Form\app\Services\FormService;
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
        $formService->registerFormElement('payment_method', fn($x) => $marketFormService::getFormElementPaymentMethod($x));
        $formService->registerFormElement('shipping_method', fn($x) => $marketFormService::getFormElementShippingMethod($x));
    }
}