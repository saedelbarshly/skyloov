<?php

namespace App\Http\Controllers\api;

use App\Filters\TaskFilter;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepositoryInterface;


class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilter $filter): JsonResponse
    {
        try {
            $tasks = $this->taskRepository->getAllTasks($filter, 10);
            return response()->json(TaskResource::collection($tasks)->response()->getData(true));
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something went wrong!"], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskRepository->createTask($request->only(['title', 'description', 'status', 'due_date']));
            return response()->json(['message' => 'Task created successfully ✅', 'data' => new TaskResource($task)], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something went wrong!"], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $task = $this->taskRepository->getTaskById($id);
            return response()->json(new TaskResource($task));
        } catch (\Throwable $th) {
            return response()->json(['message' => "Task not found!"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, $id): JsonResponse
    {
        try {
            $task = $this->taskRepository->updateTask($id, $request->only(['title', 'description', 'status', 'due_date']));
            return response()->json(['message' => 'Task updated successfully ✅', 'data' => new TaskResource($task)], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Task not found!"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->taskRepository->deleteTask($id);
            return response()->json(['message' => 'Task deleted successfully ✅']);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Task not found!"], 404);
        }
    }
}
