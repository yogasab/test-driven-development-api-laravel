<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    public const NOT_STARTED = 'not_started';
    public const PENDING = 'pending';
    public const STARTED = 'started';

    protected $fillable = ['title', 'todo_list_id', 'status', 'label_id'];

    public function todo_list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }
}
