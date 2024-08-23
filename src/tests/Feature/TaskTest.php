<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    protected function tearDown(): void
    {
        Task::truncate();
    }

    /**
     * 正常系
     * タスク一覧を取得できる
     */
    public function test_index(): void
    {
        $tasks = Task::factory()->count(10)->create([
            'user_id' => Auth::id()
        ]);
        $response = $this->getJson('api/tasks');

        $response
            ->assertOk()
            ->assertJsonCount($tasks->count());
    }

    /**
     * 正常系
     * タスクを作成できる
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
     * 異常系
     * タイトルが空の場合はタスクを作成できない
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
                'title' => 'タイトルが入力されていません。'
            ]);
    }

    /**
     * 異常系
     * タイトルが文字数制限を超えた場合はタスクを作成できない
     */
    public function test_store_limit_length_title(): void
    {
        $data = [
            'title' => str_repeat('あ', 41)
        ];
        $response = $this->postJson('api/tasks', $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'タイトルが40文字を超えています。'
            ]);
    }

    /**
     * 正常系
     * タスクのタイトルを更新できる
     */
    public function test_update(): void
    {
        $task = Task::factory()->create([
            'user_id' => Auth::id()
        ]);
        $task->title = 'rewrite';
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertNoContent();
    }

    /**
     * 異常系
     * タイトルが空の場合はタスクを更新できない
     */
    public function test_update_required_title(): void
    {
        $task = Task::factory()->create([
            'user_id' => Auth::id()
        ]);
        $task->title = '';
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'タイトルが入力されていません。'
            ]);
    }

    /**
     * 異常系
     * タイトルが文字数制限を超えた場合はタスクを更新できない
     */
    public function test_update_limit_length_title(): void
    {
        $task = Task::factory()->create([
            'user_id' => Auth::id()
        ]);
        $task->title = str_repeat('あ', 41);
        $response = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'タイトルが40文字を超えています。'
            ]);
    }

    /**
     * 正常系
     * タスクを削除できる
     */
    public function test_delete(): void
    {
        $task = Task::factory()->count(10)->create([
            'user_id' => Auth::id()
        ]);

        $response = $this->deleteJson('api/tasks/1');
        $response->assertNoContent();

        $response = $this->getJson('api/tasks');
        $response->assertJsonCount($task->count() - 1);
    }

    /**
     * 正常系
     * ステータスを更新できる
     */
    public function test_updateDone(): void
    {
        $task = Task::factory()->create([
            'user_id' => Auth::id()
        ]);
        $task->is_done = true;
        $response = $this->patchJson("api/tasks/update-done/{$task->id}", $task->toArray());

        $response
            ->assertNoContent();
    }

    /**
     * 異常系
     * is_doneが空の場合はステータスを更新できない
     */
    public function test_updateDone_required(): void
    {
        $task = Task::factory()->create([
            'user_id' => Auth::id()
        ]);
        $task->is_done = '';
        $response = $this->patchJson("api/tasks/update-done/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'is_done' => 'id_doneが空の状態です。'
            ]);
    }

    /**
     * 異常系
     * is_doneの型が真偽値でない場合はステータスを更新できない
     */
    public function test_updateDone_limit_type(): void
    {
        $task = Task::factory()->create([
            'user_id' => Auth::id()
        ]);
        $task->is_done = '2';
        $response = $this->patchJson("api/tasks/update-done/{$task->id}", $task->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'is_done' => 'is_doneは真偽値である必要があります。'
            ]);
    }
}
