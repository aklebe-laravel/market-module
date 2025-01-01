@php
    use Illuminate\Http\Resources\Json\JsonResource;
    use Modules\Form\app\Forms\Base\ModelBase;
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase as NativeObjectBaseLivewire;

    /**
     *
     * @var bool $visible maybe always true because we are here
     * @var bool $disabled enabled or disabled
     * @var bool $read_only disallow edit
     * @var bool $auto_complete auto fill user inputs
     * @var string $name name attribute
     * @var string $label label of this element
     * @var mixed $value value attribute
     * @var mixed $default default value
     * @var bool $read_only
     * @var string $description
     * @var string $css_classes
     * @var string $css_group
     * @var string $x_model optional for alpine.js
     * @var string $livewire
     * @var array $html_data data attributes
     * @var array $x_data
     * @var int $element_index
     * @var JsonResource $object
     * @var ModelBase $form_instance
     * @var NativeObjectBaseLivewire $form_livewire
     */
@endphp
<div class="form-group form-label-group {{ $css_group }} mb-3">
    @unless(empty($label))
        <label>{{ $label }}</label>
    @endunless
    @unless(empty($description))
        <div class="form-text decent">{{ $description }}</div>
    @endunless
    <div class="form-control-info {{ $css_classes }}"
         class="form-control {{ $css_classes }}"
         @if($disabled) disabled="disabled" @endif
         @if($read_only) read_only @endif
         @foreach($html_data as $k => $v) data-{{ $k }}="{{ $v }}" @endforeach
         @foreach($x_data as $k => $v) x-{{ $k }}="{{ $v }}" @endforeach

    >
        @foreach($object->nextOffers as $nextOffer)
            @php
                $dateFormat = app('system_base')->formatDate($nextOffer->updated_at);
                $dateFormat .= ' ' . app('system_base')->formatTime($nextOffer->updated_at);

            @endphp
            <div>
                <a href="{{ route('manage-data', ['modelName' => 'Offer', 'modelId' => $nextOffer->shared_id]) }}">
                    {{ sprintf(__("Offer created at: %s"), $dateFormat) }}
                </a> -
                {{ sprintf(__("Creator '%s'"), $nextOffer->createdByUser->name) }} -
                {{ sprintf(__("Product Owner '%s'"), $nextOffer->addressedToUser->name) }}
            </div>
        @endforeach
    </div>
</div>