<?php

namespace App\Models;

use App\Events\Task\TaskAssignedToUser;
use App\Events\Task\TaskStatusChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subtask extends Model
{

    public $fillable = [
        'task_id', 'user_id', 'content', 'due_date', 'completed_at'
    ];
    /**
     * The attributes that are mutable to dates.
     *
     * @var array
     */
    protected $dates = [
        'due_date', 'completed_at', 'start_date'
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

            // event(new TaskAssignedToUser($this, $user));
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

    public function markAsCompleted()
    {
        if (!$this->completed_at) {
            return tap($this->update(['completed_at' => now()]), function () {
                // event(new TaskStatusChanged($this));
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
            return tap($this->update(['completed_at' => null]), function () {
                // event(new TaskStatusChanged($this));
            });
        }
    }

    /**
     * Mark the task as applicable.
     *
     * @return boolean
     */
    public function markAsApplicable()
    {
        if ($this->applicable) {
            return $this->update(['applicable' => true]);
        }
    }

    /**
     * Mark the task as not applicable.
     *
     * @return boolean
     */
    public function markAsNotApplicable()
    {
        if ($this->applicable) {
            $this->update(['applicable' => false]);
        }
    }

    /**
     * Filter completed subtasks.
     *
     * @param $query
     */
    public function scopeCompleted($query)
    {
        $query->whereNotNull('completed_at');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
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
    public function isApplicable()
    {
        return $this->applicable === 0;
    }

    public function isNotApplicable()
    {
        return $this->applicable === 0;
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}