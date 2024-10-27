<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Omnipay\Omnipay;

class CheckoutController extends Controller
{
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();
        return view('guest.cart.checkout', compact('user'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Retrieve cart items with their associated product prices
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product') // Eager load the product relationship
            ->get();

        // Check if the cart is not empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('guest.cart.index')->withErrors('Your cart is empty.');
        }

        // Check stock for each cart item
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('guest.cart.index')
                    ->withErrors("The quantity for {$item->product->product_name} exceeds available stock. Only {$item->product->stock} left.");
            }
        }

        // Calculate total amount based on the cart items in PHP
        $totalAmountInPHP = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Convert total amount to USD
        $exchangeRate = 0.017; // Example exchange rate, update as needed
        $totalAmountInUSD = number_format($totalAmountInPHP * $exchangeRate, 2, '.', '');

        // Redirect to the payment page if the payment method is PayPal
        if ($request->payment_method === 'paypal') {
            return redirect()->route('checkout.payment', ['amount' => $totalAmountInUSD, 'cartItems' => $cartItems->toArray(), 'user' => $user]);
        }

        return redirect()->route('guest.index')->with('success', 'Order placed successfully!');
    }




    public function payment(Request $request)
    {
        $cartItems = $request->input('cartItems');
        $amount = $request->input('amount');

        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId(config('services.paypal.client_id'));
        $gateway->setSecret(config('services.paypal.secret'));
        $gateway->setTestMode(config('services.paypal.mode') === 'sandbox');

        $response = $gateway->purchase([
            'amount' => $amount,
            'currency' => 'USD',
            'returnUrl' => route('checkout.payment.success', ['cartItems' => $cartItems]),
            'cancelUrl' => route('checkout.payment.cancel'),
        ])->send();

        if ($response->isRedirect()) {
            $response->redirect();
        } else {
            return redirect()->route('guest.cart.index')->with('error', 'Payment failed. Please try again.');
        }
    }




    public function paymentSuccess(Request $request)
    {
        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId(config('services.paypal.client_id'));
        $gateway->setSecret(config('services.paypal.secret'));
        $gateway->setTestMode(config('services.paypal.mode') === 'sandbox');

        // Capture the payment using the payment ID and payer ID
        $response = $gateway->completePurchase([
            'payer_id' => $request->get('PayerID'),
            'payment_id' => $request->get('paymentId'),
            'transactionReference' => $request->get('paymentId'),
        ])->send();

        if ($response->isSuccessful()) {
            // Now, create the order since payment was successful
            $cartItems = $request->input('cartItems'); // Get cart items from the request
            $user = Auth::user();

            

            // Use the total amount calculated in your application
            $totalAmountInPHP = 0;
        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);
            $itemPrice = $product->price;
            $totalAmountInPHP += $item['quantity'] * $itemPrice;
        }

            // Create the order with customer details
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmountInPHP, // Use the amount calculated in PHP
                'payment_status' => 'Paid', // Set to paid now
                'shipping_status' => 'Pending',
                'name' => $user->name,
                'email' => $user->email,
                'address' => $request->input('address'), // Use the address from the request
                'payment_method' => 'paypal',
                'payment_id' => $request->get('paymentId'), // Set the payment ID
                'transaction_reference' => $request->get('paymentId'), // Use paymentId as transactionReference
            ]);

            // Store each item in the order
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Use the price from the Product model
                ]);
            

                // Reduce the stock of the product
                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }

            // Clear the cart after placing the order
            Cart::where('user_id', $user->id)->delete();

            Mail::to($order->email)->send(new OrderConfirmationMail($order));

            return redirect()->route('guest.index')->with('success', 'Order placed successfully!');
        }

        return redirect()->route('guest.cart.index')->with('error', 'Payment failed. Please try again.');
    }

    public function paymentCancel()
    {
        return redirect()->route('checkout.show')->withErrors('Payment cancelled.');
    }
}
