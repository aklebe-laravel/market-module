@php
    /**
     * @var array $cartItemsByUsers
     */
    $livewireTable = 'market::data-table.offer-by-shopping-cart-item';
    $livewireForm = 'market::form.shopping-cart-item';
    $contentView = 'market::components.data-tables.tables.offers-potential-content';
@endphp
@include('website-base::page', [
    'title' => 'Offers',
    'contentView' => $contentView,
])
