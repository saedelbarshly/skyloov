<?php

namespace App\Repositories;

use App\Models\Task;
use App\Filters\TaskFilter;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks(TaskFilter $filter, int $perPage)
    {
        return Task::filter($filter)->orderBy('due_date', 'asc')->paginate($perPage);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function getTaskById(int $id)
    {
        return Task::findOrFail($id);
    }

    public function updateTask(int $id, array $data)
    {
        $task = $this->getTaskById($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask(int $id)
    {
        $task = $this->getTaskById($id);
        $task->delete();
        return $task;
    }
}
