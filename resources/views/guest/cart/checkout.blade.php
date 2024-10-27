@extends('layouts.non_account')

@section('content')
<div class="container">
    <h1>Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
            <option value="" disabled selected>Select Payment Method</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>
        <div class="form-group d-flex">
        <button type="submit" class="btn btn-secondary">Proceed to Payment</button>
    <a href="{{ route('guest.cart.index') }}" class="btn btn-secondary ml-auto">Cancel</a>
</div>
    </form>
</div>
@endsection
