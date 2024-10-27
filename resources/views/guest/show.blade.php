@extends('layouts.non_account')

@section('content')

<div class="container mt-1">
    <h1 class="text-center">{{ $product->product_name }}</h1>
    <br>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-secondary">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-secondary">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Left half: Product Image -->
        <div class="col-md-6 text-center">
            @csrf
            @method('PUT')

            @if($product->product_pic)
                <a href="javascript:void(0);">
                    <img id="product_pic_preview" src="{{ asset('storage/' . $product->product_pic) }}"
                        class="img-fluid" alt="{{ $product->product_name }}"
                        style="max-height: 400px; border-radius: 15px;">
                </a>
            @else
                <a href="javascript:void(0);" onclick="document.getElementById('product_pic_input').click();">
                    <img id="product_pic_preview" src="{{ asset('storage/product_pics/default-product-image.png') }}"
                        class="img-fluid" alt="Default Image" style="max-height: 400px;">
                </a>
            @endif
        </div>

        <!-- Right half: Product Details -->
        <div class="col-md-6">
            <!-- Product Description -->
            <div class="form-group">
                <div class="text-wrap" style="overflow-wrap: break-word;">
                    <p style="font-size: 1.1rem;">{{ $product->description }}</p>
                </div>
                <hr style="border: 1px solid white;">
            </div>

            <!-- Product Pricing and Discount -->
            <div class="form-group">
                @if($product->discount > 0)
                    <div class="d-flex justify-content-between">
                        <p class="text mb-0" style="font-size: 1.75rem; text-decoration: line-through;">
                            ₱ {{ number_format($product->origprice, 2) }}
                        </p>
                        <p class="text-danger mb-0" style="font-size: 1.75rem;">
                            ₱ {{ number_format($product->origprice * (1 - $product->discount / 100), 2) }}
                        </p>
                    </div>
                    <p class="badge bg-secondary mt-2 text-center" style="font-size: 1rem; display: block; width: 100%;">
                        {{ $product->discount }}% Off
                    </p>
                @else
                    <p class="text text-center" style="font-size: 1.75rem;">
                        ₱ {{ number_format($product->origprice, 2) }}
                    </p>
                @endif
            </div>

            <!-- Product Stock with Quantity Input -->
            <form method="POST" action="{{ route('cart.add') }}">
            <div class="form-group d-flex align-items-center justify-content-between">
                <div class="stock-info d-flex align-items-center">
                    @if ($product->stock > 0)
                    <span class="stock-label font-weight-bold" style="font-size: 1.25rem;">Stock:</span>
                        <span class="stock-value ml-2" style="font-size: 1.25rem;">{{ $product->stock }}</span>
                    @else
                        <span class="text-danger font-weight-bold" style="font-size: 1.25rem;">Out of Stock</span>
                    @endif
                </div>

                @if ($product->stock > 0)
                    <input type="number" name="quantity" value="0" class="form-control w-25" style="width: 80px; margin-left: 20px;">
                @endif
            </div>
            <hr style="border: 1px solid white;">

            <!-- Add to Cart and Buy Now buttons -->
            <div class="d-flex justify-content-end align-items-center mt-4">
                @auth
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-secondary ml-3">Add to Cart</button>
                        </div>
                    </form>

                    <form method="POST" action="#">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-secondary ml-3">Buy Now</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary ml-3">Add to Cart</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary ml-3">Buy Now</a>
                @endauth
            </div>

        </div>
    </div>
</div>

@endsection
