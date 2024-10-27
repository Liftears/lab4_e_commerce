@extends('layouts.non_account')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white">Your Shopping Cart</h1>
        <a href="{{ route('checkout.show') }}" class="btn btn-secondary">Proceed to Checkout</a>
    </div>

    @if(session('success'))
    <div class="alert alert-secondary mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-secondary mt-3">
        {{ session('error') }}
    </div>
@endif


    @if($cart->isNotEmpty())
        <table class="table mt-3">
            <thead class="table-dark">
                <tr>
                    <th class="text-white">Product Name</th>
                    <th class="text-white">Quantity</th>
                    <th class="text-white">Price</th>
                    <th class="text-white">Total</th>
                    <th class="text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPrice = 0;
                @endphp
                @foreach($cart as $item)
                    <tr class="text-white">
                        <td>{{ $item->product->product_name }}</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                       style="width: 60px;" class="form-control d-inline">
                        </td>
                        <td>₱ {{ number_format($item->product->price, 2) }}</td>
                        <td>₱ {{ number_format($item->quantity * $item->product->price, 2) }}</td>
                        <td>
                            <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                            </form>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this item from the cart?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @php
                        $totalPrice += $item->quantity * $item->product->price;
                    @endphp
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3">
            <h4 class="text-white">Total: ₱ {{ number_format($totalPrice, 2) }}</h4>
        </div>
    @else
        <p></p>
    @endif
</div>
@endsection
