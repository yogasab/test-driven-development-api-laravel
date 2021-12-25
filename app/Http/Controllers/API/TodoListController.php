<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

    public function show(TodoList $list)
    {
        return response($list);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $list = TodoList::create($request->all());
        return response($list, Response::HTTP_CREATED);
    }
}
