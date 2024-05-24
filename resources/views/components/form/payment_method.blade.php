@php
    $list = [];
    $collection = \Modules\Market\app\Models\PaymentMethod::orderBy('code', 'ASC')->get();
    foreach ($collection as $item) {
        $list[] = [
            'id' => $item->id,
            'label' => __('payment_method_' . $item->code),
        ];
    }
@endphp
@include('form::components.form.select', [
    'options' => app('system_base')->toHtmlSelectOptions($list, ['label'], 'id', [\Modules\Form\app\Forms\Base\ModelBase::UNSELECT_RELATION_IDENT => __('No choice')]),
    ])
