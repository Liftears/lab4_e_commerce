@php
    $layout = 'layouts.non_account'; 
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $layout = 'layouts.admin';
        }
    }
@endphp

@extends($layout)


@section('content')
<div class="container">
    <h1>Edit Profile</h1>

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

    <form method="POST" action="{{ route('profile.updateuser') }}">
        @csrf

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control bg-secondary text-light" 
                value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control bg-secondary text-light" 
                value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-secondary">Update Profile</button>
        </div>
        <hr style="border: 1px solid white;">
    </form>

    <h2>Change Password</h2>
    <form method="POST" action="{{ route('profile.password') }}" class="mt-6 space-y-6">
    @csrf
    @method('POST')

    <div class="form-group">
    <input type="hidden" name="email" value="{{ old('email', $user->email) }}" required>
</div>

    <div class="form-group">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" class="form-control bg-secondary text-light" required>
        @if ($errors->updatePassword->has('current_password'))
            <div class="alert alert-danger mt-2">{{ $errors->updatePassword->first('current_password') }}</div>
        @endif
    </div>

    <div class="form-group">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" class="form-control bg-secondary text-light" required>
        @if ($errors->updatePassword->has('password'))
            <div class="alert alert-danger mt-2">{{ $errors->updatePassword->first('password') }}</div>
        @endif
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-secondary text-light" required>
        @if ($errors->updatePassword->has('password_confirmation'))
            <div class="alert alert-danger mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</div>
        @endif
    </div>

    <div class="flex items-center gap-4">
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-secondary">Update Password</button>
        </div>
        <hr style="border: 1px solid white;">
    </div>
</form>



    <hr>

    <h2>Delete Account</h2>
    <p>Once your account is deleted, all of your data will be permanently removed. Please be sure to download any information you want to keep.</p>
    <div class="d-flex justify-content-end">
    <button class="btn btn-danger" 
            onclick="event.preventDefault(); document.getElementById('delete-account-form').submit();">
        Delete Account
    </button>
    </div>
    <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
    <hr style="border: 1px solid white;">
</div>
@endsection
