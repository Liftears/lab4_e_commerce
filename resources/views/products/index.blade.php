@extends('layouts.admin')

@section('content')

<div class="container mt-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Products</h1>
        <div class="d-flex justify-content-center">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-secondary">
            <span class="d-none d-md-inline">Add Product</span>
            <span class="d-inline d-md-none">Add</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-secondary">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<div class="text-center mb-4">
    <div id="category-list" class="d-flex flex-wrap justify-content-center">
        @foreach ($categories->take(5) as $category)
            <a href="{{ route('products.category', $category->id) }}" class="badge rounded-pill bg-secondary text-light me-3 mb-3" style="font-size: 1.25rem; padding: 10px;">
                {{ $category->name }}
            </a>
        @endforeach

        @if ($categories->count() > 5)
            <a href="javascript:void(0)" id="show-more" class="badge rounded-pill bg-secondary text-light me-3 mb-3" style="font-size: 1.25rem; padding: 10px;">
                ...
            </a>
        @endif
    </div>
</div>

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-3 mb-4">
                <a href="{{ route('products.edit', $product->id) }}" class="card-link">
                    <div class="card bg-dark text-white">
                        <div class="position-relative">
                            @if($product->product_pic)
                                <img src="{{ asset('storage/' . $product->product_pic) }}" class="card-img-top"
                                    alt="{{ $product->product_name }}">
                            @else
                                <img src="{{ asset('storage/product_pics/default-product-image.png') }}" class="card-img-top"
                                    alt="Default Image">
                            @endif
                            @if($product->discount > 0)
                                <span class="badge bg-secondary position-absolute top-0 start-100 translate-middle"
                                    style="font-size: 0.8rem; right: 5px; top: 10px;">
                                    {{ $product->discount }}% Off
                                </span>
                            @endif
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                @if(strlen($product->product_name) > 15)
                                    {{ substr($product->product_name, 0, 15) }}<span class="view-product_name">...</span>
                                @else
                                    {{ $product->product_name }}
                                @endif
                            </h5>
                            <p class="card-text text-justify">
                                @if(strlen($product->description) > 28)
                                    {{ substr($product->description, 0, 28) }}<span class="view-description">...</span>
                                @else
                                    {{ $product->description }}
                                @endif
                                <br>
                                <span style="font-size: 1rem; font-weight: bold;">
                                    @if($product->discount > 0)
                                        <span class="d-none d-lg-inline"
                                            style="text-decoration: line-through; display: inline-block;">
                                            ₱{{ number_format($product->origprice, 2, '.', ',') }}</span>
                                        <span class="d-none d-lg-inline" style="text-decoration: none;">&nbsp;
                                        </span>

                                        <span class="text-danger">
                                            ₱{{ number_format($product->origprice * (1 - $product->discount / 100), 2, '.', ',') }}
                                        </span>
                                    @else
                                        <span>₱{{ number_format($product->origprice, 2, '.', ',') }}</span>
                                    @endif
                                </span><br>
                                @if($product->stock > 0)
                                    <span class="text-success">In Stock</span>
                                @else
                                    <span class="text-danger">Out of Stock</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{ $products->links() }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let showMoreButton = document.getElementById('show-more');
        let categoryList = document.getElementById('category-list');
        let allCategories = @json($categories); // Get all categories as a JSON object

        showMoreButton.addEventListener('click', function() {
            // Clear existing categories and display all
            categoryList.innerHTML = '';

            allCategories.forEach(function(category) {
                let categoryLink = document.createElement('a');
                categoryLink.href = '/products/category/' + category.id; // Update the URL
                categoryLink.className = 'badge rounded-pill bg-secondary text-light me-2 mb-2';
                categoryLink.style.fontSize = '1.25rem';
                categoryLink.style.padding = '10px';
                categoryLink.textContent = category.name;

                categoryList.appendChild(categoryLink);
            });

            // Add the '...' link back to collapse
            let collapseButton = document.createElement('a');
            collapseButton.href = 'javascript:void(0)';
            collapseButton.id = 'collapse-button';
            collapseButton.className = 'badge rounded-pill bg-secondary text-light me-2 mb-2';
            collapseButton.style.fontSize = '1.25rem';
            collapseButton.style.padding = '10px';
            collapseButton.textContent = '...';
            categoryList.appendChild(collapseButton);

            // Remove the 'show more' button
            showMoreButton.remove();

            // Add event listener to collapse button
            collapseButton.addEventListener('click', function() {
                categoryList.innerHTML = ''; // Clear current categories

                // Show only the first 5 categories again
                allCategories.slice(0, 5).forEach(function(category) {
                    let categoryLink = document.createElement('a');
                    categoryLink.href = '/products/category/' + category.id; // Update the URL
                    categoryLink.className = 'badge rounded-pill bg-secondary text-light me-2 mb-2';
                    categoryLink.style.fontSize = '1.25rem';
                    categoryLink.style.padding = '10px';
                    categoryLink.textContent = category.name;

                    categoryList.appendChild(categoryLink);
                });

                // Add the '...' link back
                categoryList.appendChild(showMoreButton);
            });
        });
    });
</script>

@endsection
