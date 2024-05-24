@php
    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.accept-offer';
@endphp
<button
        class="btn btn-primary"
        {{--        wire:click="createOfferBinding" type="button" --}}
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {'accept-offer': {livewire_id: '{{ $this->getId() }}', name: '{{ $this->getName() }}', item_id: {{ data_get($editFormModelObject, 'id') }}}})"
>
    {{ __("Accept Offer") }}
</button>