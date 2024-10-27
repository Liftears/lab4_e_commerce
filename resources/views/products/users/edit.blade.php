@extends('layouts.admin')

@section('content')
    <h2>Edit User: {{ $user->name }}</h2>
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        
        <div class="form-group">
            <label for="roles">Roles:</label>
            <select name="roles[]" class="form-control" multiple required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                        {{ $role->role_name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group d-flex">
    <button type="submit" class="btn btn-secondary">Update</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary ml-auto">Cancel</a>
</div>
    </form>
@endsection
