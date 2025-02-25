@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.form.reject-offer';

    $messageBoxParams1 = [
        'reject-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-danger"
        {{--        wire:click="createOfferBinding" type="button" --}}
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Reject Offer") }}
</button>