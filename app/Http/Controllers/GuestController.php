<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $products = Product::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('product_name', 'like', '%' . $query . '%');
        })->paginate(12);

        $categories = Category::all();

        return view('guest.index', compact('products', 'categories'));
    }

    public function filterByCategory($id)
{
    // Fetch the category (optional, but good for validation)
    $category = Category::findOrFail($id);

    // Fetch products belonging to this category
    $products = Product::where('category_id', $id)->paginate(10);

    // Fetch all categories for the view
    $categories = Category::all();

    return view('guest.index', compact('products', 'categories'));
}

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('guest.show', compact('product'));
    }

    public function helloworl()
    {
        return view('guest.helloworl');
    }
}
