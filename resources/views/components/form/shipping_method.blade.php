@php
    use Modules\Market\app\Models\ShippingMethod;

    $list = [];
    $collection = ShippingMethod::orderBy('code', 'ASC')->get();
    foreach ($collection as $item) {
        $list[] = [
            'id' => $item->id,
            'label' => __('shipping_method_' . $item->code),
        ];
    }
@endphp
@include('form::components.form.select', [
        'options' => app('system_base')->toHtmlSelectOptions($list, ['label'], 'id', app('system_base')->selectOptionsSimple[app('system_base')::selectValueNoChoice]),
    ])
