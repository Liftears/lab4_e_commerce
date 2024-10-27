@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Order Management</h1>
        <a href="{{ route('admin.orders.downloadReport') }}" class="btn btn-secondary">Completed Orders Report</a>
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

    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
        <label for="status" class="text-white">Status:</label>
        <select name="status" id="status" onchange="this.form.submit()" class="form-select">
            <option value="" {{ request('status') === '' ? 'selected' : '' }}>All</option>
            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Shipped" {{ request('status') === 'Shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="Delivered" {{ request('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
        </select>
    </form>

    <table class="table mt-3">
        <thead class="table-dark">
            <tr>
                <th class="text-white">Order ID</th>
                <th class="text-white">Username</th>
                <th class="text-white">Order Date</th>
                <th class="text-white">Total Amount</th>
                <th class="text-white">Status</th>
                <th class="text-white">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="text-white">
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>₱ {{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->shipping_status }}</td>
                    <td>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
