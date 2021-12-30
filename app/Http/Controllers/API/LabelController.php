<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function store(LabelRequest $request)
    {
        $label = Label::create($request->validated());
        return $label;
    }
}
