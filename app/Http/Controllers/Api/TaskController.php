<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    // Display a listing of the tasks
    public function index()
    {
        $tasks = Task::all(); // Get all tasks
        return response()->json($tasks); // Return as JSON
    }

    // Show the form for creating a new task (optional for API)
    public function create()
    {
        // This method can be removed for an API
    }

    // Store a newly created task in storage
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', // Validate the request
            'description' => 'nullable|string', // Optional description
            'category_id' => 'required|exists:categories,id', // Validate category existence
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::create($request->all()); // Create a new task
        return response()->json($task, 201); // Return the created task
    }

    // Display the specified task
    public function show($id)
    {
        $task = Task::find($id); // Find the task by ID
        if ($task) {
            return response()->json($task);
        }
        return response()->json(['message' => 'User not found', 'error' => true, 'redirect' => ''], 404);
    }

    // Show the form for editing the specified task (optional for API)
    public function edit($id)
    {
        // This method can be removed for an API
    }

    // Update the specified task in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'exists:categories,id',
        ]);

        $task = Task::findOrFail($id); // Find the task by ID
        $task->update($request->all()); // Update the task
        return response()->json($task); // Return the updated task
    }

    // Remove the specified task from storage
    public function destroy($id)
    {
        $task = User::findOrFail($id); // Find the task by ID
        $task->delete(); // Delete the task
        return response()->json(null, 204); // Return a 204 No Content response
    }

    public function add_tasks(Request $request)
    {
        $user = User::with(['tasks' => function ($query) {
            $query->whereNull('deleted_at');
        }])->where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                "message" => "You have already created a task, Go To My tasks",
                "error" => true,
                "redirect" => "/my-tasks",
                "user" => ["id" => $user->id, "name" => $user->name, "email" => $user->email, "username" => $user->username, "user" => ["id" => $user->id, "name" => $request->name, "email" => $request->email, "username" => $request->username]]
            ], 403); // Return a 204 No Content response
        } else {
            // Session::put('user', ["id" => $user->id, "name" => $user->name, "email" => $user->email, "username" => $user->username]);
            $user = User::create(["name" => $request->name, "email" => $request->email, "username" => $request->username ]);
            $tasks = [];
            foreach($request->tasks as $task) {
                $tasks[] = [
                    "description" => $task["description"],
                    "category_id" => $task["category_id"],
                    "created_by" => $user->id
                ];
            }
            $user->tasks()->createMany($tasks);
            return response()->json(["message" => "Task Created", "redirect" => "my-tasks", "user" => ["id" => $user->id, "name" => $request->name, "email" => $request->email, "username" => $request->username]], 200); // Return a 204 No Content response
        }
    }
}
