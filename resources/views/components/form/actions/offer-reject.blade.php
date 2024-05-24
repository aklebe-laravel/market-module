@php
    $messageBoxRejectItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.reject-offer';
@endphp
<button
        class="btn btn-danger"
        {{--        wire:click="createOfferBinding" type="button" --}}
        x-on:click="messageBox.show('{{ $messageBoxRejectItemPath }}', {'reject-offer': {livewire_id: '{{ $this->getId() }}', name: '{{ $this->getName() }}', item_id: {{ data_get($editFormModelObject, 'id') }}}})"
>
    {{ __("Reject Offer") }}
</button>