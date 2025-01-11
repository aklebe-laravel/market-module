@php
    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.reject-offer';

    $messageBoxParams1 = [
        'reject-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'itemId' => data_get($editFormModelObject, 'id'),
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