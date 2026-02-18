@php
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;

    /**
     * @var NativeObjectBase $form_instance
     * @var array $data
     */

    $ratingContainerName = 'ratingContainer';
    $ratingContainer = [
        $ratingContainerName => [
            $data['name'] => (int)$data['value'],
        ]
    ];

@endphp
@include('form::components.form.hidden')

<div x-data="{!! htmlspecialchars(json_encode($ratingContainer)) !!}" class="mb-3 cursor-default">
    <label>{{ $data['label'] }}</label><br/>
    @include('form::components.alpine.rating', ['ratingAlpineName' => 'form_data.'.$data['name']])
</div>
