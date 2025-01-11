@php
    use Illuminate\Database\Eloquent\Model;
    use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;

    /**
     * @var BaseDataTable $this
     * @var Model $item
     * @var string $name
     * @var mixed $value
     **/

    // path for messageBox.config
    $jsMessageBoxClaimItemPath = app('system_base_module')->getModelSnakeName($this->getEloquentModelName()) . '.data-table.claim';

    $userHasAlreadyRated = $item->hasCurrentUserAlreadyRated();
    $userRatingVisible = Auth::user()->hasAclResource('rating.user.visible');
    $userIsItself = (Auth::id() == data_get($item, 'id'));

    $messageBoxParams1 = [
        'accept-rating' => [
            'name' => 'market::form.user-rating',
            'itemId' => data_get($item, 'id'),
        ],
        'item' => $item->toArray(),
    ];
@endphp
@if($userRatingVisible && $this->editable && !$userIsItself)
    {{--Rate Button--}}
    <button
            class="btn btn-sm {{ $userHasAlreadyRated ? 'btn-outline-secondary' : 'btn-outline-primary' }} {{ data_get($this->mobileCssClasses, 'button', '') }}"
            x-on:click="messageBox.show('user.default.rating', {{ json_encode($messageBoxParams1) }} )"
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