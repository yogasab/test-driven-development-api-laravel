<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index()
    {
        return response(['lists' => []]);
    }
}
