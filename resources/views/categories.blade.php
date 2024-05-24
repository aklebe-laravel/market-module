<div class="row category-list">
    @foreach($children as $category)
        <div class="col-12 col-md-6 category">
            <div class="item">
                <div class="title">
                    <a href="{{ $category->web_uri }}">{{ $category->name }}</a>
                </div>
                <div class="image">
                    @if ($image = $category->getContentImage(\Modules\Market\app\Models\Category::IMAGE_MAKER))
                        <a href="{{ $category->web_uri }}">
                            <img src="{{ $image->final_url }}" alt="{{ $image->file_name }}"
                                 title="Image for category {{ $category->name }}"/>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>