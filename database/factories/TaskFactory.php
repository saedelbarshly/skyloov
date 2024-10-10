<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(TaskStatus::values()),
            // 'due_date' => Carbon::now()->addDays(rand(1, 365)),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
