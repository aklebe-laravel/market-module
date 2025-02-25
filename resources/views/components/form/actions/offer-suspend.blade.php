@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */
    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.form.offer-suspend';

    $messageBoxParams1 = [
        'offer-suspend' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
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