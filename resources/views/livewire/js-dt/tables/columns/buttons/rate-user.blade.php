@php
    /**
     * @var \Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable $this
     * @var \Illuminate\Database\Eloquent\Model $item
     * @var string $name
     * @var mixed $value
     **/

    // path for messageBox.config
    $jsMessageBoxClaimItemPath = app('system_base_module')->getModelSnakeName($this->getModelName()) . '.data-table.claim';
    // $jsMessageBoxDeleteItemPath = \Modules\SystemBase\Helpers\ModelHelper::getSnakeName($this->getModelName()) . '.data-table.delete';
    // $fullJsVarName = 'messageBox.config.' . $jsMessageBoxDeleteItemPath;

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