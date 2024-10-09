<?php

namespace App\Http\Controllers\api;

use App\Filters\TaskFilter;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskReqeust;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilter $filter)
    {
        try {
            $tasks = Task::filter($filter)->paginate(10);
            return TaskResource::collection($tasks)->response()->getData(true);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskReqeust $request)
    {
        try {
            $task = Task::create($request->only(['title', 'description', 'status', 'due_date']));
            return response()->json(['message' => 'Task created successfully ✅','data' => new TaskResource($task)], 201);  
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskReqeust $request, Task $task)
    {
         try {
            $task->update($request->only(['title', 'description', 'status', 'due_date']));
            return response()->json(['message' => 'Task updated successfully ✅','data' => new TaskResource($task)], 200);  
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully ✅'], 200);    
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }
}
