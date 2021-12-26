<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['title', 'todo_list_id'];

    public function todo_list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }
}
