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
<div class="form-group form-label-group {{ $data['css_group'] }}">
    <div class="box-offer-status-{{ $data['value'] }} p-4">
        <div class=" text-lg">
            @include('form::components.form.element-parts.label')
            <span class="offer-status-{{ $data['value'] }}">
                {{ __('OFFER_STATUS_' . $data['value']) }}
            </span>
        </div>
        <div class="form-text decent">
            {{ __('OFFER_STATUS_' . $data['value'] . '_DESCRIPTION') }}<br/>
            {{ $data['description'] }}
        </div>
    </div>
</div>