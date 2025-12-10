<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Notifications\TaskCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('category')->latest()->get();
        $categories = Category::all();

        return view ('dashboard', compact('tasks', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:pending,completed',
            'priority'=>'required|in:high,medium,low',
            'due_date'=>'nullable|date',
            'category_id'=>'required|exists:categories,id',
        ]);

        $validated['user_id'] = $request->user()->id;

        $task=Task::create($validated);

        $web_hook_url = env('WEB_HOOK_URL');

        Notification::route('slack', $web_hook_url)
            ->notify(new TaskCreated($task));

        return redirect()->route('dashboard')->with('success', 'Task Created!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) abort(403);
        return $task->load('category');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //$this->authorize('update', $task);

        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:pending,completed',
            'priority'=>'required|in:high,medium,low',
            'due_date'=>'nullable|date',
            'category_id'=>'nullable|exists:categories,id',
        ]);

        $task->update($validated);

        return response()->json(['message' => 'Task Updated', 'task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //$this->authorize('delete', $task);
        $task->delete();

        return response()->json(['message'=>'Deleted Successfully']);
    }
}
