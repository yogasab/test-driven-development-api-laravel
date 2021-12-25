<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = TodoList::all();
        return response(['lists' => $lists]);
    }
}
