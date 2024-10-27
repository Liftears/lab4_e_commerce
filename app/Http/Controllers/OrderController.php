<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\OrderStatusUpdate;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Notification;

class OrderController extends Controller
{
    // Show all orders with filtering capability
    public function index(Request $request)
{
    $status = $request->input('status');

    // Fetch all orders if no specific status is selected
    $orders = Order::with('user')
                    ->when($status, function ($query) use ($status) {
                        return $query->where('shipping_status', $status);
                    }, function ($query) {
                        // This part ensures that all orders are fetched when no status is selected
                        return $query;
                    })
                    ->get();

    return view('products.orders.index', compact('orders'));
}

public function edit($orderId)
{
    $order = Order::findOrFail($orderId);
    $orderItems = $order->orderItems; // Fetch associated order items

    return view('products.orders.edit', compact('order', 'orderItems'));
}

public function updateStatus(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);
    $request->validate([
        'shipping_status' => 'required|string',
    ]);

    // Update the shipping status
    $order->update([
        'shipping_status' => $request->shipping_status,
    ]);

    $order->update(['status' => $request->status]);

    // Notify the user about the status update
    Notification::send($order->user, new OrderStatusUpdate($order));

    return redirect()->route('admin.orders.index', $orderId)->with('success', 'Shipping status updated successfully.');
}


    // Download sales report for completed orders
    public function downloadReport()
{
    $orders = Order::where('shipping_status', 'Completed')->with('user')->get();

    if ($orders->isEmpty()) {
        return back()->withErrors('No completed orders available for download.');
    }

    // Prepare the CSV header
    $csvData = "Order ID,Username,Customer Name,Email,Address,Payment Method,Total Amount,Order Date\n";

    // Populate each order's data into the CSV
    foreach ($orders as $order) {
        $csvData .= "{$order->id},{$order->user->name},{$order->name},{$order->email},{$order->address},{$order->payment_method},{$order->total_amount},{$order->created_at}\n";
    }

    $filename = "completed_orders_report.csv";
    return Response::make($csvData, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ]);
}

public function myOrders()
{
    $user = Auth::user();
    $orders = Order::where('user_id', $user->id)->with('orderItems.product')->get();

    return view('guest.orders.index', compact('orders'));
}

public function show($id)
{
    $order = Order::with('orderItems.product')->findOrFail($id);
    return view('guest.orders.show', compact('order')); // Adjusted to point to the guest/orders/show view
}


}

