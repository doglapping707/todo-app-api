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
     * Can get a list of tasks.
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
     * Can create new tasks.
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
     * abnormality
     * Cannot create a new task if the title is empty.
     */
    public function test_store_required_title(): void
    {
        $data = [
            'title' => ''
        ];
        $response = $this->postJson('api/tasks', $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'The title field is required.'
            ]);
    }

    /**
     * abnormality
     * Cannot create a new task if the title exceeds the character limit.
     */
    public function test_store_limit_length_title(): void
    {
        $data = [
            'title' => str_repeat('あ', 256)
        ];
        $response = $this->postJson('api/tasks', $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'The title field must not be greater than 255 characters.'
            ]);
    }

    /**
     * normality
     * Can update the task title.
     */
    public function test_update_title(): void
    {
        $task = Task::factory()->create();
        $task->title = 'rewrite';
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertNoContent();
    }

    /**
     * abnormality
     * Cannot update task if title is empty.
     */
    public function test_update_required_title(): void
    {
        $task = Task::factory()->create();
        $task->title = '';
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'The title field is required.'
            ]);
    }

    /**
     * abnormality
     * Cannot update task if the title exceeds the character limit.
     */
    public function test_update_limit_length_title(): void
    {
        $task = Task::factory()->create();
        $task->title = str_repeat('あ', 256);
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'The title field must not be greater than 255 characters.'
            ]);
    }

    /**
     * normality
     * Can delete tasks.
     */
    public function test_delete(): void
    {
        $task = Task::factory()->count(10)->create();

        $response = $this->deleteJson('api/tasks/1');
        $response->assertNoContent();

        $response = $this->getJson('api/tasks');
        $response->assertJsonCount($task->count() - 1);
    }

    /**
     * normality
     * Can update is_done.
     */
    public function test_updateDone(): void
    {
        $task = Task::factory()->create();
        $task->is_done = '1';
        $response = $this->patchJson("api/tasks/update-done/{$task->id}", $task->toArray());

        $response
            ->assertNoContent();
    }

    /**
     * abnormality
     * Cannot update is_done if is_done is empty.
     */
    public function test_updateDone_required(): void
    {
        $task = Task::factory()->create();
        $task->is_done = '';
        $response = $this->patchJson("api/tasks/update-done/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'is_done' => 'The is done field is required.'
            ]);
    }

    /**
     * abnormality
     * Cannot update is_done If type is not boolean.
     */
    public function test_updateDone_limit_type(): void
    {
        $task = Task::factory()->create();
        $task->is_done = '2';
        $response = $this->patchJson("api/tasks/update-done/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'is_done' => 'The is done field must be true or false.'
            ]);
    }
}
