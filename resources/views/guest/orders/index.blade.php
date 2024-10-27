@extends('layouts.non_account')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Your Order History</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-secondary mt-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-secondary mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($orders->isEmpty())
        <p class="text-white">You have no orders yet.</p>
    @else
        <table class="table mt-3">
            <thead class="table-dark">
                <tr>
                    <th class="text-white">Order ID</th>
                    <th class="text-white">Total Amount</th>
                    <th class="text-white">Shipping Status</th>
                    <th class="text-white">Order Date</th>
                    <th class="text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="text-white">
                        <td>{{ $order->id }}</td> 
                        <td>₱ {{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ $order->shipping_status }}</td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>
                            <form action="{{ route('my.orders.show', $order->id) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Show</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
