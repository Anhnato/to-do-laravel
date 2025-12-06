<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vaidated = $request->validate([
            'name'=>'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $vaidated['name'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        \App\Models\Task::where('category_id', $category->id)->update(['category_id' => null]);

        $category->delete();

        return response()->json(['message'=>'Deleted successfully']);
    }
}
