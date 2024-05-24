@php
    /**
     * @var array $contentViewParameters
     */
    $livewireKey1 = \Modules\SystemBase\app\Services\LivewireService::getKey('manage-default-form-key');
@endphp
{{--Form--}}
<div>
    @livewire($livewireForm, [
        'relatedLivewireDataTable' => $livewireTable,
    ],
    key($livewireKey1))
</div>

<div>
    @foreach($cartItemsByUsers as $userId => $cartItemsData)
        @php
            $cartItems = $cartItemsData['items'];
            $user = $cartItemsData['user'];
        @endphp
        <h3 class="text-sm">{{ __(':count items by user: :user', ['count' => count($cartItems), 'user' => $user->name]) }}</h3>

        {{-- Data Table--}}
        <div>
            @php $livewireKey2 = \Modules\SystemBase\app\Services\LivewireService::getKey('manage-default-dt-key-' . $userId); @endphp
            @livewire($livewireTable, [
                'relatedLivewireForm' => $livewireForm,
                'headerView' => '',
                'editable' => true,
                'selectable' => false,
                'hasCommands' => true,
                'selectedItems' => $cartItems,
                'userId' => (int)$userId,
//                'footerActions' => 'inc.offers.actions-cart-items',
            ],
            key($livewireKey2))
        </div>
    @endforeach
</div>

<div>
    @if($footerView ?? null)
        @include($footerView)
    @endif
</div>