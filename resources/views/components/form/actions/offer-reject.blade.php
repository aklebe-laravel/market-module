@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName(app('system_base')->getSimpleClassName($this->getObjectEloquentModelName())) . '.form.reject-offer';

    $messageBoxParams1 = [
        'reject-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-danger btn-offer-reject"
        {{--        wire:click="createOfferBinding" type="button" --}}
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Reject Offer") }}
</button>