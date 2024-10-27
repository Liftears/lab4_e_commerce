
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <h1 style="color: #333; text-align: center;">Thank You!</h1>
        <h2 style="color: #666; text-align: center;">For ordering in</h2>
        <h1 style="color: #333; text-align: center;">Eternal Nap Online</h1>
        
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Transaction Reference:</strong> {{ $order->transaction_reference }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Username:</strong> {{ $order->user->name }}</p>
        <p><strong>Full Name:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>

        <h3 style="border-top: 1px solid #ddd; padding-top: 15px;">Order Summary</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="text-align: left; border-bottom: 1px solid #ccc; padding-bottom: 8px;">Item Name</th>
                    <th style="text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 8px;">Quantity</th>
                    <th style="text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 8px;">Unit Price</th>
                    <th style="text-align: right; border-bottom: 1px solid #ccc; padding-bottom: 8px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td style="padding: 8px 0;">{{ $item->product->product_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: center;">₱ {{ number_format($item->price, 2) }}</td>
                        <td style="text-align: right;">₱ {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="font-weight: bold; margin-top: 15px; text-align: right;">Total Amount: ₱ {{ number_format($order->total_amount, 2) }}</p>

        <h3 style="border-top: 1px solid #ddd; padding-top: 15px;">Terms and Conditions</h3>
        <ul style="padding-left: 20px;">
            <li>All items are subject to availability.</li>
            <li>We are processing your order and will notify you once it is shipped.</li>
        </ul>

        <p style="margin-top: 20px;">If you have any questions, please contact us at <strong>{{ env('MAIL_FROM_ADDRESS') }}</strong>.</p>
    </div>

</body>

