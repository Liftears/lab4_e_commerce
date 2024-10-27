@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white">Order ID: {{ $order->id }}</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-secondary mt-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mt-4">
        <h3 class="text-white">Order Details</h3>
        <p><strong>Name:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
        <p><strong>Total Amount:</strong> ₱ {{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
        <p><strong>Current Shipping Status:</strong> {{ $order->shipping_status }}</p>
    </div>

    <div class="mt-4">
        <h3 class="text-white">Order Items</h3>
        <table class="table mt-3">
            <thead class="table-dark">
                <tr>
                    <th class="text-white">Picture</th>
                    <th class="text-white">Name</th>
                    <th class="text-white">Quantity</th>
                    <th class="text-white">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderItems as $item)
                    <tr class="text-white">
                        <td>
                            @if ($item->product->product_pic && file_exists(public_path('storage/' . $item->product->product_pic)))
                                <img src="{{ asset('storage/' . $item->product->product_pic) }}" 
                                     alt="{{ $item->product->product_name }}" 
                                     class="img-fluid img-thumbnail" 
                                     style="width: 50px; height: auto;">
                            @else
                            @endif
                        </td>
                        <td>
                            <span>{{ $item->product->product_name }}</span>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>₱ {{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-4">
    @csrf
    <div class="d-flex justify-content-between align-items-center">
        <div class="form-group me-2">
            <select name="shipping_status" id="shipping_status" class="form-select">
                <option value="Pending" {{ $order->shipping_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Shipped" {{ $order->shipping_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="Delivered" {{ $order->shipping_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="Completed" {{ $order->shipping_status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">Update</button>
    </div>
</form>

</div>
@endsection
