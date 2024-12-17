@php
    /**
     * @var BaseDataTable $this
     * @var Model $item
     * @var string $name
     * @var mixed $value
     **/

    use Illuminate\Database\Eloquent\Model;
    use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;

    // path for messageBox.config
    $jsMessageBoxClaimItemPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.data-table.claim';

    $userHasAlreadyRated = $item->hasCurrentUserAlreadyRated();
    $userRatingVisible = Auth::user()->hasAclResource('rating.user.visible');
@endphp
@if($userRatingVisible && $this->editable)
    {{--Rate Button--}}
    <button
            class="btn btn-sm {{ $userHasAlreadyRated ? 'btn-outline-secondary' : 'btn-outline-primary' }} {{ data_get($this->mobileCssClasses, 'button', '') }}"
            x-on:click="messageBox.show('user.default.rating', {'accept-rating': {name: 'market::form.user-rating', item_id: '{{ data_get($item, 'id') }}' }, product: {{ $item->toJson() }} })"
            title="{{ __('Rate') }}"
    >
        <span class="bi {{ $userHasAlreadyRated ? 'bi-star-fill' : 'bi-star' }}"></span>
        @if($userHasAlreadyRated)
            <span class="user-already-rated-icon-block2 text-success">
                <span class="bi bi-check"></span>
            </span>
        @endif
    </button>
@endif