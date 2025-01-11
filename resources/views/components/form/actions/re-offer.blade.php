@php
    $messageBoxReOfferPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.re-offer';

    $messageBoxParams1 = [
        're-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'itemId' => data_get($editFormModelObject, 'id'),
        ],
    ];
@endphp
<button
        class="btn btn-secondary"
        x-on:click="messageBox.show('{{ $messageBoxReOfferPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Create New Offer") }}
</button>