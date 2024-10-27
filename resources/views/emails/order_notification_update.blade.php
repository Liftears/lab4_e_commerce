<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; width: 100%; height: 100%;">
    <table width="100%" height="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f4; margin: 0; padding: 0;">
        <tr>
            <td align="center" valign="top" style="padding: 20px 0;">
                <table width="500" cellpadding="0" cellspacing="0" style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; max-width: 500px; width: 100%;">
                    <tr>
                        <td>
                            <h1 style="margin: 0 0 10px; font-size: 24px; color: #333;">Hello, {{ $order->name }}!</h1>
                            <p style="margin: 10px 0; color: #666;">Your order #{{ $order->id }} status has been updated to <strong>{{ $order->shipping_status }}</strong>.</p>
                            <a href="{{ url('/my-orders') }}" style="display: inline-block; margin: 20px 0; padding: 10px 20px; background-color: #000; color: #fff; text-decoration: none; border-radius: 4px;">View Order</a>
                            <hr>

                            <p style="margin: 10px 0; color: #666; text-align: left;">Thank you for shopping with us!</p>
                            <p style="margin: 10px 0; color: #666; text-align: left;">Regards,<br><strong>Eternal Nap Online</strong></p>

                            <p style="margin: 10px 0; color: #666; text-align: left;">If you're having trouble clicking the "View Order" button, copy and paste the URL below into your web browser:</p>
                            <p style="margin: 10px 0; color: #666;"><a href="{{ url('/my-orders') }}" style="color: #000;">{{ url('/my-orders') }}</a></p>


                            <div style="margin-top: 20px; font-size: 12px; color: #999;">
                                <p>If you have any questions, please contact us at {{ config('mail.from.address') }}.</p>
                                <p>&copy; {{ date('Y') }} Eternal Nap Online. All rights reserved.</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
