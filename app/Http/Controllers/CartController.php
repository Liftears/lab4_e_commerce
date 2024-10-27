<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Get the current user
    $user = Auth::user();

    // Find the product
    $product = Product::find($request->product_id);

    // Check if the product is already in the cart
    $cartItem = Cart::where('user_id', $user->id)
        ->where('product_id', $request->product_id)
        ->first();

    if ($cartItem) {
        // Update quantity if already exists
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        // Create new cart item
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    return redirect()->back()->with('success', $product->product_name . ' added to cart successfully!');
}



public function view()
{
    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->with('product')->get(); // Assuming you have set up a relationship in your Cart model

    return view('guest.cart.index', compact('cart'));
}



    public function update(Request $request, $id)
{
    $cartItem = Cart::findOrFail($id);
    $product = $cartItem->product;

    // Check if the requested quantity exceeds the available stock
    if ($request->quantity > $product->stock) {
        return redirect()->back()->with('error', 'Requested quantity exceeds available stock for ' . $product->product_name);
    }

    // Update the cart item's quantity
    $cartItem->update([
        'quantity' => $request->quantity,
    ]);

    return redirect()->route('guest.cart.index')->with('success', $product->product_name . ' quantity updated successfully.');
}


public function remove($id)
{
    // Find the cart item by ID
    $cartItem = Cart::find($id);
    $product = $cartItem->product;
    
    // Check if the cart item exists
    if ($cartItem) {
        $cartItem->delete(); // Remove the item from the database
        return redirect()->back()->with('success', $product->product_name . ' removed successfully');
    }

    return redirect()->back()->with('error', 'Product not found in cart');
}

}

