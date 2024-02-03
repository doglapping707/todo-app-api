<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * normality
     * Can get a list of tasks
     */
    public function test_index(): void
    {
        $tasks = Task::factory()->count(10)->create();
        $response = $this->getJson('api/tasks');

        $response
            ->assertOk()
            ->assertJsonCount($tasks->count());
    }
}
