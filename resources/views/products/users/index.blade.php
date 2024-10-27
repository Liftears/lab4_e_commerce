@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white">User Management</h1>
        <a href="{{ route('users.create') }}" class="btn btn-secondary">Add New User</a>
    </div>

    <table class="table mt-3">
        <thead class="table-dark">
            <tr>
                <th class="text-white">Name</th>
                <th class="text-white">Email</th>
                <th class="text-white">Roles</th>
                <th class="text-white">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-white">
                <td>Your Account</td>
                <td>{{ $currentUser->email }}</td>
                <td>{{ $currentUser->roles->pluck('role_name')->join(', ') }}</td>
                <td>
                    <a href="{{ route('users.edit', $currentUser->id) }}" class="btn btn-secondary">Edit</a>
                    <form action="{{ route('users.destroy', $currentUser->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>

            @foreach ($admins as $user)
                @if ($user->id !== $currentUser->id)
                    <tr class="text-white">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('role_name')->join(', ') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach

            @foreach ($customers as $user)
                <tr class="text-white">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('role_name')->join(', ') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
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
