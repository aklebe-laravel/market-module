@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.form.accept-offer';

    $messageBoxParams1 = [
        'accept-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, $this->getFormInstance()::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-primary"
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Accept Offer") }}
</button>