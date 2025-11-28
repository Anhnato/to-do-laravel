<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    //Middleware for login required
    // public function __construct(){
    //     $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vaidated = $request->validate([
            'name'=>'required|string|max:255',
            'user_id'=>'required|exists:users,id'
        ]);

        $category = Category::create($vaidated);

        return response()->json($category, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'=>'required|string|max:255',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message'=>'Deleted successfully']);
    }
}
