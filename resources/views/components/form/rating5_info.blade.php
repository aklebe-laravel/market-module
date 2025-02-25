@php
    use Illuminate\Http\Resources\Json\JsonResource;
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase as NativeObjectBaseLivewire;

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
     * @var JsonResource $object
     * @var NativeObjectBaseLivewire $form_livewire
     */

    $ratingContainerName = 'ratingContainer';
    $ratingContainer = [
        $ratingContainerName => [
            $name => (int)$value,
        ]
    ];

@endphp
@include('form::components.form.hidden', [])

<div x-data="{!! htmlspecialchars(json_encode($ratingContainer)) !!}" class="mb-3 cursor-default">
    <label>{{ $label }}</label><br/>
    @include('form::components.alpine.rating', ['ratingAlpineName' => 'form_data.'.$name])
</div>
