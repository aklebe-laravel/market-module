@php
    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.accept-offer';

    $messageBoxParams1 = [
        'accept-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'itemId' => data_get($editFormModelObject, 'id'),
        ],
    ];
@endphp
<button
        class="btn btn-primary"
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Accept Offer") }}
</button>