@php
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @var NativeObjectBase $form_instance
     * @var JsonResource $object
     * @var array $data
     */

    $ratingContainerName = 'ratingContainer';

    // make data copy for rating
    $ratingData = $data;
    $ratingData['x_model'] = $ratingContainerName;
@endphp
{{--force form_data here for rating--}}
<div x-data="{ form_data:$wire.dataTransfer, {{ $ratingContainerName }} : { clickable: {{ $data['disabled'] ? 'false' : 'true' }} }}"
     class="mb-3">

    @include('form::components.form.hidden', ['data' => $ratingData])
    @include('form::components.form.element-parts.label')
    @include('form::components.alpine.rating', ['ratingAlpineName' => 'form_data.'.$data['name']])
    @include('form::components.form.element-parts.description')
</div>
