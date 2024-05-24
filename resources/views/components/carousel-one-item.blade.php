@php
    /**
     * @var string $carouselId
     * @var \Modules\WebsiteBase\app\Models\MediaItem[]|\Illuminate\Database\Eloquent\Collection $mediaItems
     */

@endphp

<div id="{{ $carouselId }}" class="carousel slide" data-bs-ride="false">
    @php $i = 0; @endphp
    <div class="carousel-indicators">
        @foreach($mediaItems as $image)
            <button type="button" class="shadow {{ (!$i) ? 'active' : '' }}" data-bs-target="#{{ $carouselId }}"
                    data-bs-slide-to="{{ $i }}" @if($i == 0) aria-current="true"
                    @endif aria-label="{{ $image->description }}"></button>
            @php $i++; @endphp
        @endforeach
    </div>
    <div class="carousel-inner">
        @php $i = 0; @endphp
        @foreach($mediaItems as $image)
            <div class="carousel-item {{ $i++ == 0 ? 'active' : '' }}">
                <img src="{{ $image->final_url }}" class="d-block w-100" title="{{ $image->description }}"
                     alt="{{ $image->file_name }}">
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ $image->description }}</h5>
                    <p>{{ $image->pivot->content_code }} - {{ $image->meta_description }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon shadow" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
        <span class="carousel-control-next-icon shadow" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
