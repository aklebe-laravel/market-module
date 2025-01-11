@php
    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.offer-suspend';

    $messageBoxParams1 = [
        'offer-suspend' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'itemId' => data_get($editFormModelObject, 'shared_id'),
        ],
    ];

@endphp
<button
        type="button"
        class="btn btn-outline-danger"
        x-on:click="messageBox.show('{{ $messageBoxDeleteItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Suspend") }}
</button>