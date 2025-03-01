@php
    use Illuminate\Support\Carbon;
    use Modules\Acl\app\Models\AclResource;
    use Modules\Acl\app\Services\UserService;
    use Modules\Market\app\Models\Product;use Modules\WebsiteBase\app\Services\WebsiteService;

    /**
     * @var Product $product
     */

    if (!$product) {
        return;
    }

    $price = $product->getExtraAttribute('price');
    $userHasAlreadyRated = $product->hasCurrentUserAlreadyRated();
    $timeLocaleStartedAt = $product->started_at ? Carbon::parse($product->started_at)->toDateString() : null;
    $timeLocaleExpiredAt = $product->expired_at ? Carbon::parse($product->expired_at)->toDateString() : null;
    $timeLocaleExpiredAtDiff = $product->expired_at ? Carbon::parse($product->expired_at)->shortRelativeToNowDiffForHumans() : null;

    /** @var UserService $userService */
    $userService = app(UserService::class);

    $messageBoxParams1 = [
        'accept-rating' => [
            'name' => 'market::form.product-rating',
            'itemId' => $product->getKey(),
        ],
        'item' => $product->toArray()
    ];
@endphp

<div class="container-fluid product-box {{ $product->is_test ? 'opacity-75' : '' }}">
    <div class="row">

        @if (!$product)
            <div class="alert alert-danger">
                {{ __('Product not found') }}
            </div>
        @else
            <div class="col-lg">

                @include('market::components.carousel-one-item', [
                    'carouselId' => 'productImageMakerCarousel',
                    'mediaItems' => $product->getContentImages(Product::IMAGE_MAKER)->get(),
                ])

            </div>
            <div class="col-lg">
                <div class="container-fluid info-container">
                    <div class="row {{ $product->is_test ? 'bg-danger-subtle' : '' }}">
                        <div class="col-12 col-md-9 text-center text-md-start">
                            <h2>
                                {!! $product->is_test ? '<span class="text-danger">[TEST]</span> ' : '' !!}
                                {{ $product->name }}
                                @if($userService->hasUserResource(\Illuminate\Support\Facades\Auth::user(), AclResource::RES_DEVELOPER))
                                    <span class="text-sm decent">(ID:{{ $product->id }})</span>
                                @endif
                            </h2>
                        </div>
                        @if (app('market_settings')->canShowProductRating())
                            <div class="col-12 col-md-3 text-center text-md-end"
                                 x-data="{ratingContainer:{rating5:{{ $product->rating5 }}, show_value: false, user_has_rated: {{ json_encode($userHasAlreadyRated) }} }}">
                                <span class="btn" x-on:click="messageBox.show('product.default.rating', {{ json_encode($messageBoxParams1) }} )">
                                    @include('form::components.alpine.rating')
                                </span>
                            </div>
                        @endif
                        @if ($timeLocaleExpiredAtDiff)
                            <div class="col-12 text-danger">
                                <span class="bi bi-stopwatch"></span>
                                {{ __('Time Limited') }} :
                                {{--                            {{ $timeLocaleStartedAt ?? '...' }} - {{ $timeLocaleExpiredAt ?? '...' }}--}}
                                @if ($timeLocaleExpiredAtDiff)
                                    {{ __('Expired At') }}
                                    {{ $timeLocaleExpiredAtDiff ?? '-' }}
                                @endif
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="row price-row {{ !$price ? 'text-success' : '' }}">
                                <div class="col col-lg-2">
                                    {{ __('Price') }}
                                </div>
                                <div class="price col col-lg-4 text-end font-semibold">
                                    {{ $product->price_formatted }}
                                </div>
                                <div class="col-lg text-center text-sm-end">
                                    @include('market::inc.add-to-cart-button', ['product' => $product])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row attribute-row">
                        <div class="col-sm">
                            {{ __('Payment Method') }}
                        </div>
                        <div class="col-sm text-sm-end">
                            @if ($product->paymentMethod)
                                {{ __('payment_method_' . $product->paymentMethod->code ?? '') }}
                                <span class="btn px-0"
                                      data-bs-toggle="popover"
                                      data-bs-trigger="hover focus"
                                      title="{{ __('Payment Method') }}: {{ __('payment_method_' . $product->paymentMethod->code ?? '') }}"
                                      data-bs-content="{{ __('payment_method_description_' . $product->paymentMethod->code ?? '') }}">
                                                    <span class="bi bi-info-circle"></span>
                                                </span>
                            @else
                                <span class="bi bi-x-circle"></span>
                            @endif
                        </div>
                    </div>
                    <div class="row attribute-row">
                        <div class="col-sm">
                            {{ __('Shipping Method') }}
                        </div>
                        <div class="col-sm text-sm-end">
                            @if ($product->shippingMethod)
                                {{ __('shipping_method_' . $product->shippingMethod->code ?? '') }}
                                <span class="btn px-0"
                                      data-bs-toggle="popover"
                                      data-bs-trigger="hover focus"
                                      title="{{ __('Shipping Method') }}: {{ __('shipping_method_' . $product->shippingMethod->code ?? '') }}"
                                      data-bs-content="{{ __('shipping_method_description_' . $product->shippingMethod->code ?? '') }}">
                                                    <span class="bi bi-info-circle"></span>
                                                </span>
                            @else
                                <span class="bi bi-x-circle"></span>
                            @endif
                        </div>
                    </div>
                    <div class="row attribute-row">
                        <div class="col-sm">
                            {{ __('Provider') }}
                        </div>
                        <div class="col-sm text-sm-end">
                            <a href="{{ $product->user->getUserProfileLink() }}">
                                {{ $product->user->name }}
                                @if (app('market_settings')->canShowUserRating())
                                    <span class="small"
                                          x-data="{ratingContainer:{rating5:{{ $product->user->rating5 }}, show_value: false}}">
                                        @include('form::components.alpine.rating')
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 short-description">
                            <h3>{{ __('Item Information') }}</h3>
                            {!! ($product->short_description) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid info-container">
                <div class="row">
                    <div class="col-12 long-description">
                        <h3>{{ __('Detailed Item Information') }}</h3>
                        {!! nl2br($product->description) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 meta-description">
                        <h3>{{ __('Meta description') }}</h3>
                        {!! nl2br($product->meta_description) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        @include('market::inc.add-to-cart-button', ['product' => $product])
                    </div>
                </div>
            </div>

            <div>
                <template
                        x-init="user.userId='{{ $product->user->shared_id }}';user.fetchObject();"
                        x-if="user.isLoaded"
                >
                    <template x-if="(crossSelling.items.length > 1)">
                        <div>
                            @php $userLink = '<a href="' . $product->user->getFrontendLink() . '">' . $product->user->name .'</a>' @endphp
                            <h3 class="">
                                {!! sprintf(__('More products by %s'), $userLink)  !!}
                                @if (app('market_settings')->canShowUserRating())
                                    <span class="small" x-data="{ratingContainer:{rating5:user.object.rating5}}">
                                        @include('form::components.alpine.rating')
                                    </span>
                                @endif
                            </h3>
                            @include('market::components.carousel-cross-selling', [
                                'class' => 'userProductsCarousel',
                                'mediaItems' => $product->getContentImages()->get(),
                            ])
                        </div>
                    </template>
                </template>
            </div>
        @endif
    </div>
</div>
