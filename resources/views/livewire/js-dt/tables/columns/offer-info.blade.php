@php
    /**
     * @var BaseDataTable $this
     * @var Model $item
     * @var string $name
     * @var mixed $value
     **/

    use Illuminate\Database\Eloquent\Model;
    use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;
@endphp
<div class="">
    <button
            class="btn-link link-primary"
            wire:click="$dispatchTo('{{ $this->relatedLivewireForm }}', 'open-form', '{id: {{ data_get($item, 'id', 0) }} }')"
    >
        {{ data_get($item, 'createdByUser.name', '???') }} <span
                class="bi bi-arrow-right"></span> {{ data_get($item, 'addressedToUser.name', '???') }}
        @include("data-table::livewire.js-dt.tables.columns.value-with-info")
    </button>
</div>

