@php
    use Illuminate\Database\Eloquent\Model;
    use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;

    /**
     * @var BaseDataTable $this
     * @var Model $item
     * @var string $name
     * @var mixed $value
     * @var array $column
     * @var array $options
     **/
@endphp
@include("data-table::livewire.js-dt.tables.columns.default")
@if(\Illuminate\Support\Facades\Auth::user()->hasAclResource('rating.user.visible'))
    <div class="small">
        @include("market::livewire.js-dt.tables.columns.rating5_info", ['value' => $item->rating5])
    </div>
@endif