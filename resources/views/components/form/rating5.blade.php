@php
    /**
     * default input text element
     *
     * @var bool $visible maybe always true because we are here
     * @var bool $disabled enabled or disabled
     * @var bool $read_only disallow edit
     * @var bool $auto_complete auto fill user inputs
     * @var string $name name attribute
     * @var string $id id attribute
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
     * @var Illuminate\Http\Resources\Json\JsonResource $object
     * @var \Modules\Form\app\Forms\Base\ModelBase $form_instance
     */

    $ratingContainerName = 'ratingContainer';
@endphp
{{--force form_data here for rating--}}
<div x-data="{ form_data:$wire.formObjectAsArray, {{ $ratingContainerName }} : { clickable: {{ $disabled ? 'false' : 'true' }} }}"
     class="mb-3">

    @include('form::components.form.hidden', ['x_model' => $ratingContainerName])
    <label>{{ $label }}</label><br/>
    @include('form::components.alpine.rating', ['ratingAlpineName' => 'form_data.'.$name])
    @unless(empty($description))
        <div class="form-text decent">{{ $description }}</div>
    @endunless

</div>
