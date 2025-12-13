<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=> [
                'required',
                'string',
                'max:50',
                Rule::unique('categories')->where(function($query){
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($category, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        abort_if($category->user_id !== Auth::id(), 403, 'You do not own this category.');

        //Start transaction
        DB::transaction(function () use ($category){
            //Unlink tasks
            Task::where('category_id', $category->id)->update(['category_id' => null]);

            //Delete category
            $category->delete();
        });

        return response()->json(null, 204);
    }
}
