<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoListRequest;
use App\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = TodoList::all();
        return response(['lists' => $lists]);
    }

    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }

    public function store(TodoListRequest $request)
    {
        $list = TodoList::create($request->all());
        return response($list, Response::HTTP_CREATED);
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('', 204);
    }

    public function update(TodoList $todo_list, TodoListRequest $request)
    {
        $todo_list->update($request->all());
        return $todo_list;
    }
}
