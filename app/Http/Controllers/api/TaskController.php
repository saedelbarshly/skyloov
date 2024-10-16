<?php

namespace App\Http\Controllers\api;

use App\Filters\TaskFilter;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilter $filter)
    {
        try {
            $tasks = Task::filter($filter)->orderBy('due_date', 'asc')->simplePaginate(10);
            return response()->json(TaskResource::collection($tasks)->response()->getData(true));
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $task = Task::create($request->only(['title', 'description', 'status', 'due_date']));
            return response()->json(['message' => 'Task created successfully ✅', 'data' => new TaskResource($task)], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);
            return response()->json(new TaskResource($task));
        } catch (\Throwable $th) {
            return response()->json(['message' => "Task not found !"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($request->only(['title', 'description', 'status', 'due_date']));
            return response()->json(['message' => 'Task updated successfully ✅', 'data' => new TaskResource($task)], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Task not found !"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully ✅']);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Task not found !"], 404);
        }
    }
}
