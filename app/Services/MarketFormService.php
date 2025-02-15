<?php

namespace Modules\Market\app\Services;

use Modules\Market\app\Models\PaymentMethod;
use Modules\Market\app\Models\ShippingMethod;
use Modules\SystemBase\app\Services\Base\BaseService;
use Modules\SystemBase\app\Services\CacheService;
use Modules\SystemBase\app\Services\SystemService;

class MarketFormService extends BaseService
{
    /**
     * @return array
     */
    public static function getFormElementShippingMethodOptions(): array
    {
        return app(CacheService::class)->rememberForever('form_element.select_shipping_method.options', function () {
            /** @var SystemService $systemService */
            $systemService = app('system_base');

            $list = [];
            $collection = ShippingMethod::orderBy('code', 'ASC')->get();
            foreach ($collection as $item) {
                $list[] = [
                    'id'    => $item->id,
                    'label' => __('shipping_method_'.$item->code),
                ];
            }

            return $systemService->toHtmlSelectOptions($list, ['label'], 'id', $systemService->selectOptionsSimple[$systemService::selectValueNoChoice]);
        });
    }

    /**
     * @param  array  $mergeData
     *
     * @return array
     */
    public static function getFormElementShippingMethod(array $mergeData = []): array
    {
        return app('system_base')->arrayMergeRecursiveDistinct([
            'html_element' => 'select',
            'options'      => static::getFormElementShippingMethodOptions(),
            'label'        => __('Shipping Method'),
            'description'  => __('Shipping Method'),
            'validator'    => [
                'nullable',
                'integer',
            ],
            'css_group'    => 'col-12 col-md-6',
        ], $mergeData);
    }

    /**
     * @return array
     */
    public static function getFormElementPaymentMethodOptions(): array
    {
        return app(CacheService::class)->rememberForever('form_element.select_payment_method.options', function () {
            /** @var SystemService $systemService */
            $systemService = app('system_base');

            $list = [];
            $collection = PaymentMethod::orderBy('code', 'ASC')->get();
            foreach ($collection as $item) {
                $list[] = [
                    'id'    => $item->id,
                    'label' => __('payment_method_'.$item->code),
                ];
            }

            return $systemService->toHtmlSelectOptions($list, ['label'], 'id', $systemService->selectOptionsSimple[$systemService::selectValueNoChoice]);
        });
    }

    /**
     * @param  array  $mergeData
     *
     * @return array
     */
    public static function getFormElementPaymentMethod(array $mergeData = []): array
    {
        return app('system_base')->arrayMergeRecursiveDistinct([
            'html_element' => 'select',
            'options'      => static::getFormElementPaymentMethodOptions(),
            'label'        => __('Payment Method'),
            'description'  => __('Payment Method'),
            'validator'    => [
                'nullable',
                'integer',
            ],
            'css_group'    => 'col-12 col-md-6',
        ], $mergeData);
    }

}