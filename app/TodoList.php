<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoList extends Model
{
    protected $guarded = ['id'];
    // protected $fillable = ['name', 'user_id', 'description'];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
