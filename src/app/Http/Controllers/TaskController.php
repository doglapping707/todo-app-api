<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateDoneTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:checkUser,task')->only([
            'updateDone',
            'update',
            'destroy'
        ]);
    }

    /**
     * タスク一覧を取得する
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return Task::where('user_id', Auth::id())->orderByDesc('id')->get();
    }

    /**
     * タスクを作成する
     *
     * @param \App\Http\Requests\StoreTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $request->merge([
            'user_id' => Auth::id()
        ]);
        $task = Task::create($request->all());

        return $task
            ? response()->json($task, 201)
            : response()->json([], 500);
    }

    /**
     * タスクを更新する
     *
     * @param \App\Http\Requests\UpdateTaskRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->title = $request->title;

        return $task->update()
            ? response()->json([], 204)
            : response()->json([], 500);
    }

    /**
     * タスクを削除する
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        return $task->delete()
            ? response()->json([], 204)
            : response()->json([], 500);
    }

    /**
     * ステータスを更新する
     *
     * @param \App\Http\Requests\UpdateDoneTaskRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDone(UpdateDoneTaskRequest $request, Task $task)
    {
        $task->is_done = $request->is_done;

        return $task->update()
            ? response()->json([], 204)
            : response()->json([], 500);
    }
}
