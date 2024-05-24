@php
    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.create-offer-binding';
@endphp
<button
        class="btn btn-primary"
        {{--        wire:click="createOfferBinding" type="button" --}}
        x-on:click="messageBox.show('{{ $messageBoxDeleteItemPath }}', {'create-offer-binding': {livewire_id: '{{ $this->getId() }}', name: '{{ $this->getName() }}', item_id: {{ data_get($editFormModelObject, 'id') }}}})"
>
    {{ __("Create Offer Binding") }}
</button>