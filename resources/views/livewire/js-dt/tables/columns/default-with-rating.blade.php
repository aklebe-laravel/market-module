@php
    /**
     * @var \Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable $this
     * @var Illuminate\Database\Eloquent\Model $item
     * @var string $name
     * @var mixed $value
     * @var array $column
     * @var array $options
     **/
@endphp
@include("data-table::livewire.js-dt.tables.columns.default")
@if(\Illuminate\Support\Facades\Auth::user()->hasAclResource('rating.product.visible'))
    <div class="small">
        @include("market::livewire.js-dt.tables.columns.rating5_info", ['value' => $item->rating5])
    </div>
@endif