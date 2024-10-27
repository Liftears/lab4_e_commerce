@extends('layouts.non_account')

@section('content')
<div class="container">
    <h1 class="text-white">Order ID: {{ $order->id }}</h1>

    <div class="mt-4">
        <h3>Order Details</h3>
        <p><strong>Total Amount:</strong> ₱ {{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
        <p><strong>Status:</strong> {{ $order->shipping_status }}</p>
    </div>

    <div class="mt-4">
        <h3>Ordered Items</h3>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th class="text-white">Product Name</th>
                    <th class="text-white">Quantity</th>
                    <th class="text-white">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr class="text-white">
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₱ {{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
