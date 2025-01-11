@php
    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.create-offer-binding';

    $messageBoxParams1 = [
        'create-offer-binding' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'itemId' => data_get($editFormModelObject, 'id'),
        ],
    ];
@endphp
<button
        class="btn btn-primary"
        x-on:click="messageBox.show('{{ $messageBoxDeleteItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Create Offer Binding") }}
</button>