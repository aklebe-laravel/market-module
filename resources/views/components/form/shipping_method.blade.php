@php
    $list = [];
    $collection = \Modules\Market\app\Models\ShippingMethod::orderBy('code', 'ASC')->get();
    foreach ($collection as $item) {
        $list[] = [
            'id' => $item->id,
            'label' => __('shipping_method_' . $item->code),
        ];
    }
@endphp
@include('form::components.form.select', [
        'options' => app('system_base')->toHtmlSelectOptions($list, ['label'], 'id', [-1 => __('No choice')]),
    ])
