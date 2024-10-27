@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Edit Category</h2>

        <div class="row justify-content-center">
        <div class="col-md-6">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>

            <div class="form-group d-flex">
    <button type="submit" class="btn btn-secondary">Update</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary ml-auto">Cancel</a>
</div>
        </form>
    </div>
    </div>
    </div>
@endsection
