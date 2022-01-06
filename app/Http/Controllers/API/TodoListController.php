<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    // @decs    Get all todo lists
    // @route   GET /api/todo-lists
    // @access  Private
    public function index()
    {
        // $lists = TodoList::all();
        // $lists = TodoList::where('user_id', auth()->id())->get();
        // $lists = TodoList::whereUserId(auth()->id())->get();
        $lists = Auth::user()->todo_lists;
        return TodoListResource::collection($lists);
    }

    // @decs    Get all todo lists
    // @route   GET /api/todo-lists/:id(int)
    // @access  Private
    public function show(TodoList $todo_list)
    {
        return new TodoListResource($todo_list);
    }

    // @decs    Create/Store Todo Lists to related loggedin user
    // @route   POST /api/todo-lists
    // @access  Private
    public function store(TodoListRequest $request)
    {
        // $request['user_id'] = auth()->id();
        // $list = TodoList::create($request->all());
        $list = Auth::user()->todo_lists()->create($request->validated());
        return new TodoListResource($list);
    }

    // @decs    Delete Todo Lists
    // @route   DELETE /api/todo-lists/:id(int)
    // @access  Private
    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return new TodoListResource($todo_list);
    }

    // @decs    Update Todo Lists
    // @route   PUT /api/todo-lists/:id(int)
    // @access  Private
    public function update(TodoList $todo_list, TodoListRequest $request)
    {
        $todo_list->update($request->all());
        return new TodoListResource($todo_list);
    }
}
