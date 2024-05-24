@php
    /**
     * @var string $class
     * @var \Modules\WebsiteBase\app\Models\MediaItem[]|\Illuminate\Database\Eloquent\Collection $mediaItems
     */

@endphp
<template x-if="crossSelling.items.length > 1">
    <div class="multi-carousel {{ $class }}">

        <div class="multi-carousel-inner">
            <div class="container-fluid">
                <div class="row">
                    <template x-for="product in crossSelling.items.slice(0, crossSelling.carouselItemsToShow())"
                              :key="product.id">
                        <div class="multi-carousel-item py-0 px-1 " :class="crossSelling.carouselItemClassColX()">
                            <div class="card">
                                <a :href="'/product/' + product.web_uri">
                                    <template x-if="product.image_maker">
                                        <img :src="product.image_maker.final_thumb_medium_url" class="d-block w-100"
                                             :title="product.name" :alt="product.image_maker.final_thumb_medium_url">
                                    </template>
                                    <template x-if="!product.image_maker">
                                        <img src="{{ themes('images/no_image_available.jpg') }}" alt="No Image"/>
                                    </template>
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title" x-text="product.name"></h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-lg-5">{{ __('Price') }}</div>
                                            <div class="col-12 col-lg-7 text-end"
                                                 x-text="product.price_formatted"></div>
                                        </div>
                                    </li>
                                    @if (app('market_settings')->canShowProductRating())
                                        <li class="list-group-item small text-center"
                                            x-data="{ratingContainer:{rating5: product.rating5}}">
                                            @include('form::components.alpine.rating')
                                        </li>
                                    @endif
                                </ul>
                                <div class="card-body">
                                    @include('market::components.cross-selling.add-to-cart-button', ['product' => $product])
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <button class="multi-carousel-control-prev btn" @click="crossSelling.carouselRotateLeft();">
            <span class="bi bi-arrow-left-circle"></span>
        </button>
        <button class="multi-carousel-control-next btn" @click="crossSelling.carouselRotateRight();">
            <span class="bi bi-arrow-right-circle"></span>
        </button>

    </div>
</template>
