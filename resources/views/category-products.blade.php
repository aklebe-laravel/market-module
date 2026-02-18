{{-- This comment will not be present in the rendered HTML --}}
@php
    use Modules\Market\app\Models\Category;
    use Modules\Market\app\Services\ShoppingCartService;

    /** @var Category $category */

    if (!$category) {
        return;
    }

    $cart = app(ShoppingCartService::class)->getCurrentShoppingCart();
    $categoryChildren = [];
    $categoryChildren = $category->children;
@endphp
<div class="row header">
    {{--    @include('inc.category.children.navi')--}}
</div>
@include('market::products')