@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName(app('system_base')->getSimpleClassName($this->getObjectEloquentModelName())) . '.form.accept-offer';

    $messageBoxParams1 = [
        'accept-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-primary"
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Accept Offer") }}
</button>