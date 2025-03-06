@php
    use Illuminate\Http\Resources\Json\JsonResource;
    use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;

    /**
     * @var NativeObjectBase $form_instance
     * @var array $data
     */

    /* @var JsonResource $object */
    $object = $form_instance->getDataSource();
@endphp
<div class="form-group form-label-group {{ $data['css_group'] }} mb-3 text-muted">
    @include('form::components.form.element-parts.label')
    @include('form::components.form.element-parts.description')
    <div class="form-control-info {{ $data['css_classes'] }}">
        @if($object->prevOffer)
            @php
                $dateFormat = app('system_base')->formatDate($object->prevOffer->updated_at);
                $dateFormat .= ' ' . app('system_base')->formatTime($object->prevOffer->updated_at);
            @endphp
            <div>
                <a href="{{ route('manage-data', ['modelName' => 'Offer', 'modelId' => $object->prevOffer->shared_id]) }}">
                    {{ sprintf(__("Offer created at: %s"), $dateFormat) }}
                </a> -
                {{ sprintf(__("Creator '%s'"), $object->prevOffer->createdByUser->name) }} -
                {{ sprintf(__("Product Owner '%s'"), $object->prevOffer->addressedToUser->name) }}
            </div>
        @endif
    </div>
</div>