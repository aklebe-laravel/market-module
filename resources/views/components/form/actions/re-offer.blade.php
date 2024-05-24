@php
    $messageBoxReOfferPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.re-offer';
@endphp
<button
        class="btn btn-secondary"
        {{--        wire:click="createOfferBinding" type="button" --}}
        x-on:click="messageBox.show('{{ $messageBoxReOfferPath }}', {'re-offer': {livewire_id: '{{ $this->getId() }}', name: '{{ $this->getName() }}', item_id: {{ data_get($editFormModelObject, 'id') }}}})"
>
    {{ __("Create New Offer") }}
</button>