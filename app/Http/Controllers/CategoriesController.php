<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
{
    $categories = Category::all();
    return view('categories.index', compact('categories'));
}


    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20|unique:categories,name',
        ]);

        Category::create($request->all());


        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('categories.edit', compact('category'));
}

public function update(Request $request, $id)
{
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $id,
    ]);

    // Find the category by ID and update it
    $category = Category::findOrFail($id);
    $category->name = $request->input('name');
    $category->save();

    // Redirect back with a success message
    return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
}

public function destroy($id)
{
    // Find the category by ID
    $category = Category::findOrFail($id);
    
    // Delete the category
    $category->delete();

    // Redirect back with a success message
    return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
}


}

