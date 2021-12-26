<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response($tasks);
    }

    public function store(Request $request)
    {
        $task = Task::create($request->all());
        return $task;
    }

    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
