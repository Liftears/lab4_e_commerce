@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Product</h1>
    @if ($errors->any())
        <div class="alert alert-secondary">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" class="form-control bg-secondary text-light"
                value="{{ old('product_name') }}">
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description"
                class="form-control bg-secondary text-light">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="origprice" class="form-control bg-secondary text-light"
                value="{{ old('price') }}">
        </div>
        <div class="form-group">
            <label for="discount">Discount: %</label>
            <input type="number" step="1" name="discount" value="0"class="form-control bg-secondary text-light"
                value="{{ old('discount') }}">
        </div>
        <div class="form-group">
    <label for="category">Category:</label>
    <select name="category_id" class="form-control" required>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" class="form-control bg-secondary text-light" value="{{ old('stock') }}">
        </div>
        <div class="form-group">
            <label for="product_pic" class="text-white">Product Image:</label>
            <div class="custom-file">
                <input type="file" name="product_pic" class="custom-file-input bg-dark text-light" id="product_pic">
                <label class="custom-file-label bg-secondary text-light" for="product_pic">Choose file...</label>
            </div>
        </div>


        <div class="form-group d-flex">
    <button type="submit" class="btn btn-secondary">Save</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary ml-auto">Cancel</a>
</div>
</div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fileInput = document.getElementById('product_pic');
        var fileLabel = document.querySelector('.custom-file-label');

        fileInput.addEventListener('change', function() {
            var fileName = fileInput.files[0] ? fileInput.files[0].name : 'Choose file';
            fileLabel.textContent = fileName;
        });
    });
</script>
@endsection