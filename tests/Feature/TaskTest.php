<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_task_can_be_created()
    {
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'status' => 'pending',
            'due_date' => now()->addDays(5),
        ]);

        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_task_can_be_updated()
    {
        $task = Task::factory()->create();

        $task->update(['title' => 'Updated Task Title']);

        $this->assertEquals('Updated Task Title', $task->fresh()->title);
    }

    public function test_task_can_be_deleted()
    {
        $task = Task::factory()->create();
        $task->delete();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }




   
}
