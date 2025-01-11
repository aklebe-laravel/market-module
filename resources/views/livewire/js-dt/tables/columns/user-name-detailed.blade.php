@php
    use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;use Modules\Market\app\Models\User;

    /**
     * @var BaseDataTable $this
     * @var User $item
     * @var string $name
     * @var mixed $value
     * @var array $column
     * @var array $options
     **/

    $aclGroupStr = implode(', ', $item->aclGroups->pluck('name')->toArray());
    if ($aclGroupStr) {
        data_set($column, 'options.popups.0.title', __('Acl Groups'));
        data_set($column, 'options.popups.0.content', $aclGroupStr);
    }
@endphp
@include("market::livewire.js-dt.tables.columns.default-with-user-rating")

