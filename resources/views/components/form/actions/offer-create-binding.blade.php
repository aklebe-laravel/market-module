@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.form.create-offer-binding';

    $messageBoxParams1 = [
        'create-offer-binding' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-primary"
        x-on:click="messageBox.show('{{ $messageBoxDeleteItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Create Offer Binding") }}
</button>