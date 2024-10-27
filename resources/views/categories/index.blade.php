@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white">Category Management</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-secondary">Add New Category</a>
    </div>

    <table class="table mt-3">
        <thead class="table-dark">
            <tr>
                <th class="text-white">Category Name</th>
                <th class="text-white">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="text-white">
                    <td>{{ $category->name }}</td>
                    <td>
    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-secondary">Edit</a>
    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</td>


                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
