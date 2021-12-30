<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Label;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{
    public function index()
    {
        $labels = auth()->user()->labels;
        return $labels;
    }

    public function store(LabelRequest $request)
    {
        $label = auth()->user()->labels()->create($request->validated());
        return $label;
    }

    public function destroy(Label $label)
    {
        $label->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(LabelRequest $request, Label $label)
    {
        $label->update($request->validated());
        return $label;
    }
}
