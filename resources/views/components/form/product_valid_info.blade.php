@php
    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Support\Carbon;
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase as NativeObjectBaseLivewire;
    use Modules\Form\app\Forms\Base\ModelBase;
    use Modules\SystemBase\app\Services\SystemService;

    /**
     *
     * @var string $name
     * @var string $label
     * @var mixed $value
     * @var bool $read_only
     * @var string $description
     * @var string $css_classes
     * @var string $x_model
     * @var string $xModelName
     * @var array $html_data
     * @var array $x_data
     * @var mixed $validator
     * @var string $css_group
     * @var JsonResource $object
     * @var ModelBase $form_instance
     * @var NativeObjectBaseLivewire $form_livewire
     */

    $_now = Carbon::now()->format(SystemService::dateIsoFormat8601);

    $xModelName = (($x_model) ? ($x_model . '.' . $name) : '');
    $_formattedValue = '';
    if ($object && $object->getKey()) {
        if (!$object->is_enabled) {
            $_formattedValue .= ' '. __('Disabled');
            $css_group .= ' alert alert-danger';
        }
        if ($object->is_locked) {
            $_formattedValue .= ' '. __('Locked');
            $css_group .= ' alert alert-danger';
        } elseif ($object->expired_at !== null) {
            $_formattedValue .= __('Will expire.');
            $css_group .= ' alert alert-warning';
        } elseif (!$object->salable) {
            $_formattedValue .= __('Not Salable');
            $css_group .= ' alert alert-danger';
        } elseif ($object->is_test || $object->expired_at) {
            if ($object->is_test) {
                $_formattedValue .= 'Test-Produkt<br>';
            }
            if ($object->expired_at) {
                $_formattedValue .= 'Begrenzte Dauer<br>';
            }
            $css_group .= ' alert alert-warning';
        } else {
            $_formattedValue .= __('Product is valid.');
            $css_group .= ' alert alert-success';
        }
    }
@endphp
<div class="form-group form-label-group {{ $css_group }}">
    <div class="form-control-info {{ $css_classes }}"
         class="form-control {{ $css_classes }}"
         @if($xModelName) x-model="{{ $xModelName }}" @endif
         @if($disabled) disabled="disabled" @endif
         @if($read_only) read_only @endif
         @foreach($html_data as $k => $v) data-{{ $k }}="{{ $v }}" @endforeach
         @foreach($x_data as $k => $v) x-{{ $k }}="{{ $v }}" @endforeach

    >{!! $_formattedValue !!}</div>
    @unless(empty($description))
        <div class="form-text decent">{{ $description }}</div>
    @endunless
</div>