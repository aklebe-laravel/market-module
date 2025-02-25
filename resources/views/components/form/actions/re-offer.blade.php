@php
    use Modules\Market\app\Http\Livewire\Form\Offer;

    /**
     * @var Offer $this
     */

    $messageBoxReOfferPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.form.re-offer';

    $messageBoxParams1 = [
        're-offer' => [
            'livewireId' => $this->getId(),
            'name' => $this->getName(),
            'offerSharedId' => data_get($editFormModelObject, static::frontendKey),
        ],
    ];
@endphp
<button
        class="btn btn-secondary"
        x-on:click="messageBox.show('{{ $messageBoxReOfferPath }}', {{ json_encode($messageBoxParams1) }} )"
>
    {{ __("Create New Offer") }}
</button>