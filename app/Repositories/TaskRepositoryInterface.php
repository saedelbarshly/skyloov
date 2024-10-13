<?php

namespace App\Repositories;

use App\Filters\TaskFilter;

interface TaskRepositoryInterface
{
    public function getAllTasks(TaskFilter $filter, int $perPage);
    public function createTask(array $data);
    public function getTaskById(int $id);
    public function updateTask(int $id, array $data);
    public function deleteTask(int $id);
}
