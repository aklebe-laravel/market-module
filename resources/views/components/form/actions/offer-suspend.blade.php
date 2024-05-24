@php
    $messageBoxDeleteItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.form.offer-suspend';
@endphp
<button
        type="button"
        class="btn btn-outline-danger"
        x-on:click="messageBox.show('{{ $messageBoxDeleteItemPath }}', {'offer-suspend': {livewire_id: '{{ $this->getId() }}', name: '{{ $this->getName() }}', item_id: '{{ data_get($editFormModelObject, 'shared_id') }}'}})"
>
    {{ __("Suspend") }}
</button>