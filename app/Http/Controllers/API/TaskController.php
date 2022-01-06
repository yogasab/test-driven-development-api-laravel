<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Task;
use App\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function index(TodoList $todo_list)
    {
        // $tasks = Task::where('todo_list_id', $todo_list->id)->get();
        $tasks = $todo_list->tasks;
        return TaskResource::collection($tasks);
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function store(Request $request, TodoList $todo_list)
    {
        // $request['todo_list_id'] = $todo_list->id;
        // $task = Task::create($request->all());
        $task = $todo_list->tasks()->create($request->all());
        return new TaskResource($task);
        // return $task;
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return response($task);
    }
}
