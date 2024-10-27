<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $products = Product::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('product_name', 'like', '%' . $query . '%');
        })->paginate(12);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function filterByCategory($id)
    {
        // Fetch the category (optional, but good for validation)
        $category = Category::findOrFail($id);

        // Fetch products belonging to this category
        $products = Product::where('category_id', $id)->paginate(10);

        // Fetch all categories for the view
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function helloworl()
    {
        return view('products.helloworl');
    }

    public function store(Request $request)
    {
        // Custom validation messages
        $customMessages = [
            'product_name.required' => 'You forgot the product name, huh?',
            'product_name.min' => 'Product name must be at least 3 characters long. Come on, make it meaningful!',
            'product_name.unique' => 'Product name already exists! Try something unique.',
            'origprice.required' => 'Original price is missing! Nobody is buying free stuff here.',
            'stock.required' => 'What? No stock? Don’t fool your customers!',
            'discount.required' => 'Discount can’t be left blank. Set it between 0 and 100.',
            'discount.numeric' => 'Discount should be a number, no funny business.',
            'discount.min' => 'Discount must be at least 0%.',
            'discount.max' => 'Discount can’t be more than 100%, this isn’t a giveaway!',
        ];

        // Validate request with custom messages
        $request->validate([
            'product_name' => [
                'required',
                'min:3',
                'unique:products,product_name',
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 255) {
                        $fail("What the! Are you writing a short story? Keep it under 255 characters!");
                    }
                }
            ],
            'origprice' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\d{1,6}(\.\d{1,2})?$/', $value)) {
                        $fail("Seriously? No one gonna buy that! You Money-hungry.");
                    } elseif ($value <= 0) {
                        $fail("Come on, make it a positive price! You're not a charity.");
                    }
                }
            ],
            'stock' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (strlen((string) $value) > 11) {
                        $fail("You planning to stock up for the apocalypse?");
                    } elseif ($value < 1) {
                        $fail("Stock can't be zero or negative! You psycho.");
                    }
                }
            ],
            'category_id' => 'required|exists:categories,id',
            'discount' => 'required|numeric|min:0|max:100',
        ], $customMessages);

        // Handle product image upload if present
        $imagePath = null;
        if ($request->hasFile('product_pic')) {
            $imagePath = $request->file('product_pic')->store('product_pics', 'public');
        }

        // Calculate the discounted price
        $discountedPrice = $request->origprice * (1 - $request->discount / 100);

        // Create the product
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'origprice' => $request->origprice,
            'price' => $discountedPrice,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
            'discount' => $request->discount,
            'product_pic' => $imagePath,
        ]);

        // Redirect with a dynamic success message
        return redirect()->route('products.index')->with('success', $request->product_name . ' created successfully.');
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $customMessages = [
            'product_name.required' => 'You forgot the product name, huh?',
            'product_name.min' => 'Product name must be at least 3 characters long. Come on, make it meaningful!',
            'product_name.unique' => 'Product name already exists! Try something unique.',
            'origprice.required' => 'Original price is missing! Nobody is buying free stuff here.',
            'stock.required' => 'What? No stock? Don’t fool your customers!',
            'discount.required' => 'Discount can’t be left blank. Set it between 0 and 100.',
            'discount.numeric' => 'Discount should be a number, no funny business.',
            'discount.min' => 'Discount must be at least 0%.',
            'discount.max' => 'Discount can’t be more than 100%, this isn’t a giveaway!',
        ];

        $request->validate([
            'product_name' => [
                'required',
                'min:3',
                'unique:products,product_name,' . $id,
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 255) {
                        $fail("What the! Are you writing a short story? Keep it under 255 characters!");
                    }
                }
            ],
            'origprice' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\d{1,6}(\.\d{1,2})?$/', $value)) {
                        $fail("Seriously? No one gonna buy that! You Money-hungry.");
                    } elseif ($value <= 0) {
                        $fail("Come on, make it a positive price! You're not a charity.");
                    }
                }
            ],
            'stock' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (strlen((string) $value) > 11) {
                        $fail("You planning to stock up for the apocalypse?");
                    } elseif ($value <= 0) {
                        $fail("Stock can't be zero or negative! You psycho.");
                    }
                }
            ],
            'category_id' => 'required|exists:categories,id',
            'discount' => 'required|numeric|min:0|max:100',
        ], $customMessages);

        $product = Product::findOrFail($id);

        if ($request->hasFile('product_pic')) {
            if ($product->product_pic) {
                \Storage::delete('public/' . $product->product_pic);
            }

            $imagePath = $request->file('product_pic')->store('product_pics', 'public');
            $product->product_pic = $imagePath;
        }

        // Calculate the discounted price
        $discountedPrice = $request->origprice * (1 - $request->discount / 100);

        // Update product details
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->origprice = $request->origprice;
        $product->price = $discountedPrice;
        $product->category_id = $request->input('category_id');
        $product->stock = $request->stock;
        $product->discount = $request->discount;

        $product->save();

        return redirect()->route('products.index')->with('success', $request->product_name . ' updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
