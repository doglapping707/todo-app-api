<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * タスクを操作できるか判定する
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return bool
     */
    public function checkUser(User $user, Task $task): bool
    {
        if ($user->id === $task->user_id) {
            return true;
        }
    }
}
