<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoList extends Model
{
    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($todo_list) {
            $todo_list->tasks->each->delete();
        });
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
