@php
    use Illuminate\Support\Carbon;
    use Modules\SystemBase\app\Services\SystemService;
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @var NativeObjectBase $form_instance
     * @var JsonResource $object
     * @var array $data
     */

    $object = $form_instance->getDataSource();
    $_now = Carbon::now()->format(SystemService::dateIsoFormat8601);

    $xModelName = (($data['x_model']) ? ($data['x_model'] . '.' . $data['name']) : '');
    $_formattedValue = '';
    if ($object && $object->getKey()) {
        if (!$object->is_enabled) {
            $_formattedValue .= ' '. __('Disabled');
            $data['css_group'] .= ' alert alert-danger';
        }
        if ($object->is_locked) {
            $_formattedValue .= ' '. __('Locked');
            $data['css_group'] .= ' alert alert-danger';
        } elseif ($object->expired_at !== null) {
            $_formattedValue .= __('Will expire.');
            $data['css_group'] .= ' alert alert-warning';
        } elseif (!$object->salable) {
            $_formattedValue .= __('Not Salable');
            $data['css_group'] .= ' alert alert-danger';
        } elseif ($object->is_test || $object->expired_at) {
            if ($object->is_test) {
                $_formattedValue .= 'Test-Produkt<br>';
            }
            if ($object->expired_at) {
                $_formattedValue .= 'Begrenzte Dauer<br>';
            }
            $data['css_group'] .= ' alert alert-warning';
        } else {
            $_formattedValue .= __('Product is valid.');
            $data['css_group'] .= ' alert alert-success';
        }
    }
    $data['css_classes'] = 'form-control-info';
@endphp
<div class="form-group form-label-group {{ $data['css_group'] }}">
{{--    <div {!! $form_instance->calcInputAttributesString($data) !!}>{!! $_formattedValue !!}</div>--}}
    <div class="form-control-info">{!! $_formattedValue !!}</div>
    @unless(empty($data['description']))
        <div class="form-text decent">{{ $data['description'] }}</div>
    @endunless
</div>