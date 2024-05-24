<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Sub Categories</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSubCategoryContent" aria-controls="navbarSubCategoryContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSubCategoryContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach ($categoryChildren as $categoryChildren)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('category-products', ['category' => $categoryChildren->web_uri]) }}">{{ $categoryChildren->name }}</a>
                    </li>
                @endforeach
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
