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
<div class="form-group form-label-group {{ $data['css_group'] }} mb-3">
    @include('form::components.form.element-parts.label')
    @include('form::components.form.element-parts.description')
    <div class="form-control-info {{ $data['css_classes'] }}">
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