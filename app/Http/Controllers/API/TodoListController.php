<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoListRequest;
use App\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function index()
    {
        // $lists = TodoList::all();
        // $lists = TodoList::where('user_id', auth()->id())->get();
        // $lists = TodoList::whereUserId(auth()->id())->get();
        $lists = Auth::user()->todo_lists;
        return response(['lists' => $lists]);
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function store(TodoListRequest $request)
    {
        // $request['user_id'] = auth()->id();
        // $list = TodoList::create($request->all());
        $list = Auth::user()->todo_lists()->create($request->validated());
        return response($list, Response::HTTP_CREATED);
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('', 204);
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function update(TodoList $todo_list, TodoListRequest $request)
    {
        $todo_list->update($request->all());
        return $todo_list;
    }
}
