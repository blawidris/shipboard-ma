<?php

namespace App\Models;

use App\Events\Task\TaskAssignedToUser;
use App\Events\Task\TaskStatusChanged;
use App\Filters\Filterable;
use Illuminate\Support\Str;

class Task extends Model
{
    use Filterable;

    /**
     * Indicates low priority task.
     *
     * @var int
     */
    const PRIORITY_LOW = 1;

    /**
     * Indicates medium priority task.
     *
     * @var int
     */
    const PRIORITY_MEDIUM = 2;

    /**
     * Indicates high priority task.
     *
     * @var int
     */
    const PRIORITY_HIGH = 3;

    /**
     * Indicates urgent priority task.
     *
     * @var int
     */
    const PRIORITY_URGENT = 4;

    /**
     * The attributes that are mutable to dates.
     *
     * @var array
     */
    protected $dates = [
        'due_date', 'completed_at', 'start_date'
    ];


    protected $casts = [
        'is_approved' => 'int'
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->uuid = Str::uuid();
        });
    }

    /**
     * Get the associated project instance.
     *
     * @return \App\Models\Project
     */
    public function getProjectAttribute()
    {
        if ($this->isMainTask()) {
            return $this->column->project;
        }

        return $this->task->column->project;
    }

    /**
     * The task belongs to a column.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    /**
     * The task belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The task has many sub-tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The sub-task belongs to a major task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The task has many comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Filter main tasks only.
     *
     * @param $query
     */
    public function scopeMainTasks($query)
    {
        // $query->whereNull('task_id');
        $query->whereNotNull('uuid');
    }

    /**
     * Filter sub-tasks only.
     *
     * @param $query
     */
    public function scopeSubTasks($query)
    {
        // $query->whereNotNull('task_id');
        return $this->hasMany(Subtask::class);
    }

    /**
     * Filter completed tasks.
     *
     * @param $query
     */
    public function scopeCompleted($query)
    {
        $query->whereNotNull('completed_at');
    }

    /**
     * Filter incompleted tasks.
     *
     * @param $query
     */
    public function scopeIncomplete($query)
    {
        $query->whereNull('completed_at');
    }

    /**
     * Filter pending tasks.
     *
     * @param $query
     */
    public function scopePending($query)
    {
        $query->whereNull('completed_at');
    }

    /**
     * Determine if the task is completed or not.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed_at !== null;
    }

    /**
     * Determine if the task is completed or not.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->is_approved == 1;
    }

    /**
     * Determine if the task is a main task.
     *
     * @return bool
     */
    public function isMainTask()
    {
        return $this->task_id === null;
    }

    /**
     * Assign the task to specified user.
     *
     * @param \App\Models\User $user
     */
    public function assignTo($user)
    {
        if ($user && $user->id !== $this->user_id) {
            $this->update([
                'user_id' => $user->id
            ]);

            event(new TaskAssignedToUser($this, $user));
        }
    }

    /**
     * Unassign the task from the current assigned user.
     *
     * @return void
     */
    public function unassignUser()
    {
        if ($this->user_id) {
            $this->update([
                'user_id' => null
            ]);
        }
    }

    /**
     * Mark the task as completed.
     *
     * @return boolean
     */
    public function markAsCompleted()
    {
        if (!$this->completed_at) {
            return tap($this->update(['completed_at' => now(), 'is_approved' => 0]), function () {
                event(new TaskStatusChanged($this));
            });
        }
    }

    /**
     * Mark the task as approved.
     *
     * @return boolean
     */
    public function markAsApproved()
    {
        if (!$this->completed_at) {
            return tap($this->update(['is_approved' => 1, 'completed_at' => now()]), function () {
                event(new TaskStatusChanged($this));
            });
        }
    }

    /**
     * Mark the task as approved.
     *
     * @return boolean
     */
    public function markAsUnApproved()
    {
        if ($this->completed_at) {
            return tap($this->update(['is_approved' => 0, 'completed_at' => null]), function () {
                event(new TaskStatusChanged($this));
            });
        }
    }

    /**
     * Mark the task as incompleted.
     *
     * @return boolean
     */
    public function markAsIncompleted()
    {
        if ($this->completed_at) {

            $this->subtasks()->whereNotNull('completed_at')->update(['completed_at' => null]);

            return tap($this->update(['completed_at' => null, 'is_approved' => null, 'column_id' => ($this->column->project->columns->min('id'))]), function () {
                event(new TaskStatusChanged($this));
            });
        }
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
