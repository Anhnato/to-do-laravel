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
    public function index(Request $request)
    {
        $userId = Auth::id();

        // CHECK SCOPE (If using API Token)
        // If the user is logged in via Token, ensure they have 'read' scope
        if($request->user()->currentAccessToken()){
            if (! $request->user()->tokenCan('tasks:read')) {
                abort(403, 'Token does not have read permissions.');
            }
        }


        $tasks = Task::where('user_id', $userId)
        ->with('category')
        ->latest()
        ->paginate(50);

        //return json for api, view for browser
        if($request->wantsJson()){
            return response()->json($tasks);
        }

        //For web view dashoard
        $categories = Category::where('user_id', $userId)->get();

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
            'category_id'=>'nullable|exists:categories,id',
        ]);

        $task=Task::create([
            ...$validated,
        'user_id' => Auth::id(),
        ]);

        if(config('services.slack.webhook_url')){
            Notification::route('slack', config('services.slack.webhook_url'))
                        ->notify(new TaskCreated($task));
        }

        if($request->wantsJson() || $request->is('api/*')){
            return response()->json($task, 201);
        }

        return redirect()->route('dashboard')->with('success', 'Task Created!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Task $task)
    {
        //Authentication ensure user owns the task
        abort_if($task->user_id !== Auth::id(), 403, 'Unauthorized access to this task.');

        return response()->json($task->load('category'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403, 'Unauthorized action.');

        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:pending,completed',
            'priority'=>'required|in:high,medium,low',
            'due_date'=>'nullable|date',
            'category_id'=>'nullable|exists:categories,id',
        ]);

        $task->update($validated);

        if($request->wantsJson()){
            return response()->json($task, 200);
        }

        return response()->json($task->load('category'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403, 'Unauthorized action.');

        // CHECK SCOPE: Does this token have permission to write/delete?
        if($request->user()->currentAccessToken()){
            if (! $request->user()->tokenCan('tasks:write') === false) {
            abort(403, 'Token is read-only.');
        }
        }


        $task->delete();

        return response()->json(null, 204);
    }
}
