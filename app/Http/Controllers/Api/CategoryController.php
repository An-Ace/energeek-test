<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category; // Import the Category model
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Display a listing of the categories
    public function index()
    {
        $categories = Category::orderBy('id', 'ASC')->get(); // Get all categories
        return response()->json(['message' => '', 'error' => false, 'redirect' => '', "data" => $categories]); // Return as JSON
    }

    // Show the form for creating a new category (optional for API)
    public function create()
    {
        // This method can be removed for an API
    }

    // Store a newly created category in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Validate the request
        ]);

        $category = Category::create($request->all()); // Create a new category
        return response()->json($category, 201); // Return the created category
    }

    // Display the specified category
    public function show($id)
    {
        $category = Category::find($id); // Find the category by ID
        if ($category) {
            return response()->json($category);
        }
        return response()->json(['message' => 'Category not Found!', 'error' => true, 'redirect' => ''], 404);
    }

    // Show the form for editing the specified category (optional for API)
    public function edit($id)
    {
        // This method can be removed for an API
    }

    // Update the specified category in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id); // Find the category by ID
        $category->update($request->all()); // Update the category
        return response()->json($category); // Return the updated category
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        $category = Category::findOrFail($id); // Find the category by ID
        $category->delete(); // Delete the category
        return response()->json(null, 204); // Return a 204 No Content response
    }
}
