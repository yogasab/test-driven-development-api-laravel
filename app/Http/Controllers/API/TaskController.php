<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Task;
use App\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    // @decs    Get all tasks
    // @route   GET /api/todo-list/:todoListId(int)/tasks
    // @access  Private
    public function index(TodoList $todo_list)
    {
        // $tasks = Task::where('todo_list_id', $todo_list->id)->get();
        $tasks = $todo_list->tasks;
        return TaskResource::collection($tasks);
    }

    // @decs    Create/Store task
    // @route   POST /api/todo-list/:todoListId(int)/tasks
    // @access  Private
    public function store(TaskRequest $request, TodoList $todo_list)
    {
        // $request['todo_list_id'] = $todo_list->id;
        // $task = Task::create($request->all());
        $task = $todo_list->tasks()->create($request->all());
        return new TaskResource($task);
    }

    // @decs    Delete Task
    // @route   DELETE /api/tasks/{taskId(int)} 
    // @access  Private
    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return new TaskResource($task);
    }

    // @decs    Update Todo Lists
    // @route   PUT /api/tasks/{taskId(int)} 
    // @access  Private
    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return new TaskResource($task);
    }
}
