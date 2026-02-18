@php
    use Illuminate\Database\Query\Builder;
    use Modules\Market\app\Models\Product;
    use Modules\SystemBase\app\Services\LivewireService;

    /**
      * @var Builder $productsBuilder
      * @var int $productsCount
      */

    if (!$productsBuilder || !$productsCount) {
        return;
    }

    $livewireKey1 = LivewireService::getKey('products-pagination-01');
    $livewireKey2 = LivewireService::getKey('products-pagination-02');
@endphp
{{--Pagination--}}
@livewire('system-base::pagination', $paginationData, key($livewireKey1))

{{--Product Listing--}}
<div class="row product-list">
    @php $i=1; @endphp
    @php /** @var Product $product */ @endphp
    @foreach($productsBuilder->forPage(data_get($paginationData, 'currentPage', 1), data_get($paginationData, 'itemsPerPage', 12))->get() as $product)
        <div class="col-sm-6 col-lg-4 product {{ $product->is_test ? 'opacity-50' : '' }}">
            <div class="item">
                <div class="image">
                    @if ($image = $product->getContentImage(Product::IMAGE_MAKER))
                        <a href="{{ route('product', $product->web_uri) }}">
                            <img src="{{ $image->final_thumb_medium_url }}" alt="{{ $image->file_name }}"
                                 title="Image for product {{ $product->name }}"/>
                        </a>
                    @endif
                </div>

                <div class="title">
                    {!! $product->is_test ? '<span class="text-danger">[TEST]</span> ' : '' !!}
                    @if (($qty = $product->getExtraAttribute('qty', 1)) > 1)
                        {{ $qty }}x
                    @endif

                    <a href="/product/{{ $product->web_uri }}">
                        {{ $product->name }}
                    </a>
                </div>
                <div class="price">
                    {{ $product->price_formatted }}
                </div>
                @if (app('market_settings')->canShowProductRating())
                    <div class="small text-center mb-3" x-data="{ratingContainer:{rating5:{{ $product->rating5 }}}}">
                        @include('form::components.alpine.rating')
                    </div>
                @endif
                <div class="text-center">
                    @include('market::inc.add-to-cart-button', ['product' => $product])
                </div>
            </div>
        </div>
        @php $i++; @endphp
    @endforeach
</div>

{{--Pagination--}}
@livewire('system-base::pagination', $paginationData, key($livewireKey2))

