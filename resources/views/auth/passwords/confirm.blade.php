@extends('layouts.non_account')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-light">
                <div class="card-header bg-secondary text-center">{{ __('Confirm Password') }}</div>

                <div class="card-body">
                    <p>{{ __('Please confirm your password before continuing.') }}</p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control bg-secondary text-light @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback text-light" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Confirm Password') }}
                            </button>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-light" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
