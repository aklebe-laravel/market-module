@php
    /** @var \Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable $this */
    /** @var string $collectionName */
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-end">
            <button
                    class="btn btn-outline-primary btn-create-and-edit-offer"
                    wire:click="$dispatchSelf('create-offer-to-user', {'userId':'{{ $this->getUserId() }}'})"
            >{{ __('Create and Edit Offer') }}</button>
        </div>
    </div>
</div>