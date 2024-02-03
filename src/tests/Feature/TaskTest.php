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

    /**
     * normality
     * Can create new tasks
     */
    public function test_store(): void
    {
        $data = [
            'title' => 'Test Post'
        ];
        $response = $this->postJson('api/tasks', $data);

        $response
            ->assertCreated()
            ->assertJsonFragment($data);
    }

    /**
     * normality
     * Can update tasks
     */
    public function test_update(): void
    {
        $task = Task::factory()->create();
        $task->title = 'rewrite';
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertNoContent();
    }


    /**
     * normality
     * Can delete tasks
     */
    public function test_delete(): void
    {
        $task = Task::factory()->count(10)->create();

        $response = $this->deleteJson('api/tasks/1');
        $response->assertNoContent();

        $response = $this->getJson('api/tasks');
        $response->assertJsonCount($task->count() - 1);
    }
}
