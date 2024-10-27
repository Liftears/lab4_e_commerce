@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Create Category</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group d-flex">
    <button type="submit" class="btn btn-secondary">Save</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary ml-auto">Cancel</a>
</div>
            </form>
        </div>
    </div>
</div>
@endsection
