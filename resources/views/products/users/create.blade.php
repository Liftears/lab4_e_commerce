@extends('layouts.admin')

@section('content')
    <h1>Add New User</h1>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="roles">Roles:</label>
            <select name="roles[]" class="form-control" multiple required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">
                        {{ $role->role_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group d-flex">
    <button type="submit" class="btn btn-secondary">Save</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary ml-auto">Cancel</a>
</div>
    </form>
@endsection
