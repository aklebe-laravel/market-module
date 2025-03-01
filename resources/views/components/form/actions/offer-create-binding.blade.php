@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName(app('system_base')->getSimpleClassName($this->getObjectEloquentModelName())) . '.form.offer-create-binding';

    $messageBoxParams1 = [
        'offer-create-binding' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-primary btn-offer-create-binding"
        x-on:click="messageBox.show('{{ $messageBoxDeleteItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Create Offer Binding") }}
</button>