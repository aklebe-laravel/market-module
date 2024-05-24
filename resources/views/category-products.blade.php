{{-- This comment will not be present in the rendered HTML --}}
@php
    /** @var \Modules\Market\app\Models\Category $category */

    if (!$category) {
        return;
    }

    $cart = app('market_settings')->getCurrentShoppingCart();
    $categoryChildren = [];
    $categoryChildren = $category->children;
@endphp
<div class="row header">
    {{--    @include('inc.category.children.navi')--}}
</div>
@include('market::products')