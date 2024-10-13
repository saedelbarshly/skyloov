<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_list_tasks(): void
    {
        Task::factory(5)->create();
        $response = $this->getJson(route('tasks.index'));
        $this->assertEquals(5, count($response->json('data')));
        $response->assertJsonStructure(['data' => ['*' => ['id', 'title', 'description', 'status', 'due_date']]]);
        $response->assertStatus(200);
    }

    public function it_can_filtered_tasks_by_status(): void
    {
        Task::factory()->create(['status' => 'completed', 'due_date' => now()->addDays(1)]);

        $response = $this->getJson('/api/tasks?status=completed');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
    public function it_can_filtered_tasks_by_dut_date(): void
    {
        $dueDate = now()->addDays(1)->toDateString();

        Task::factory()->create([
            'status' => 'completed',
            'due_date' => $dueDate
        ]);

        $response = $this->getJson("/api/tasks?due_date={$dueDate}");
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function it_can_filtered_tasks_by_title()
    {
        Task::factory()->create(['title' => 'Task One']);
        Task::factory()->create(['title' => 'Task Two']);
        Task::factory()->create(['title' => 'Special Task']);

        $filterTitle = 'Special';

        $response = $this->getJson("/api/tasks?title={$filterTitle}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Special Task']);
    }



    public function test_create_task(): void
    {
        $response = $this->postJson(route('tasks.store'), [
            'title' => 'saed',
            'status' => $this->faker->randomElement(TaskStatus::values()),
            'due_date' => Carbon::now()->addDays(rand(1, 365)),
        ]);
        $response->assertCreated()->assertJsonFragment(['title' => 'saed']);
        $this->assertDatabaseHas('tasks', ['title' => 'saed']);
    }

    public function test_task_fields_are_required()
    {
        $this->postJson(route('tasks.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'status', 'due_date']); // Check for multiple validation errors
    }


    public function test_show_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->getJson(route('tasks.show', $task->id))
            ->assertOk()
            ->assertJsonCount(1)
            ->json('data');
        $this->assertEquals($task->id, $response['id']);
        $this->assertEquals($task->title, $response['title']);
        $this->assertEquals($task->description, $response['description']);
        $this->assertEquals($task->status->value, $response['status']);
        $this->assertEquals($task->due_date->format('Y-m-d'), $response['due_date']);
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->putJson(route('tasks.update', $task->id), [
            'title' => 'new saed',
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(TaskStatus::values()),
            'due_date' => Carbon::now()->addDays(rand(1, 365)),
        ]);
        $response->assertOk()->assertJsonFragment(['title' => 'new saed']);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'new saed']);
    }


    public function test_delete_task(): void
    {
        $task = Task::factory()->create();
        $this->deleteJson(route('tasks.destroy', $task->id))
            ->assertStatus(200)
            ->assertJson(['message' => 'Task deleted successfully ✅']);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
