<?php

namespace App\Models;

use App\Events\Project\ProjectStatusChanged;
use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;

class Project extends Model
{
    use SoftDeletes, Filterable, MultiTenantable;

    /**
     * Indicates that the project is visible to only to the creator.
     *
     * @var int
     */
    const VISIBILITY_ONLY_ME = 1;

    /**
     * Indicates that the project is visible to assigned team members.
     *
     * @var int
     */
    const VISIBILITY_TEAM_MEMBERS = 2;

    /**
     * Indicates that the project is visible to all users within the organization.
     *
     * @var int
     */
    const VISIBILITY_ORGANIZATION = 3;

    /**
     * The attributes that are mutable to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date', 'end_date', 'completed_at',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            $project->uuid = Str::uuid();
        });
    }

    protected $casts = [
        'is_approved' => 'int'
    ];

    /**
     * Get project status identifier.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        // if (!$this->isNotStarted()) {
        //     return 'not started';
        // }

        if ($this->isOngoing()) {
            return 'ongoing';
        } else {
            return 'not started';
        }

        if ($this->isOverdue()) {
            return 'overdue';
        }

        if ($this->isCompleted()) {
            return 'completed';
        }

        if ($this->trashed()) {
            return 'archived';
        }
    }

    /**
     * Get project days left attribute.
     *
     * @return int|null
     */
    public function getDaysLeftAttribute()
    {
        if ($this->end_date && !$this->isCompleted() && !$this->trashed()) {
            return Carbon::now()->diffInDays($this->end_date, false);
        }
    }

    /**
     * Represent the project deadline in the following format
     * YYYY-MM-DD to YYYY-MM-DD
     *
     * @return string
     */
    public function getTimelineAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->toDateString() . ' to ' . $this->end_date->toDateString();
        }
    }

    /**
     * Filter ongoing projects.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeOngoing($query)
    {
        $query->where('end_date', '>=', now())
            ->orWhere('end_date', null)
            ->whereNull('completed_at')
            ->whereNull('deleted_at');
    }

    public function scopeNotStarted($query)
    {
        $now = Carbon::now();

        $query->where(function ($query) use ($now) {
            // Check if there are no completed tasks or subtasks
            $query->doesntHave('columns.tasks.subtasks', 'and', function ($query) {
                $query->whereNotNull('completed_at');
            })
                // Check if the project end date is not set or it's in the future
                ->where(function ($query) use ($now) {
                    $query->where('end_date', '>', $now)->orWhereNull('end_date');
                })
                // Check if the project is not marked as completed
                ->whereNull('completed_at')
                // Check if the project is not trashed
                ->whereNull('deleted_at');
        });
    }

    /**
     * Filter overdue projects.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeOverdue($query)
    {
        $query->where('end_date', '<', now())
            ->whereNull('completed_at')
            ->whereNull('deleted_at');
    }

    /**
     * Filter completed projects.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeCompleted($query)
    {
        $query->whereNotNull('completed_at')
            ->whereNull('deleted_at');
    }

    /**
     * Filter archived projects.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeArchived($query)
    {
        $query->onlyTrashed();
    }

    /**
     * Filter accessible projects by the authenticated user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeAccessible($query)
    {
        if (auth()->user()->role === User::ROLE_TENANT_OWNER) {
            return;
        }

        $query->where('visibility', Project::VISIBILITY_ORGANIZATION)
            ->orWhere('user_id', auth()->user()->id)
            ->orWhereHas('teamMembers', function ($q) {
                return $q->where('id', auth()->user()->id);
            });
    }

    /**
     * The project is created by a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The project is assigned to many team members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teamMembers()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The project has many columns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function columns()
    {
        return $this->hasMany(Column::class);
    }

    /**
     * The project has many tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Column::class);
    }

    /**
     * The project belongs to a tenant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The project is watched by many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function watchers()
    {
        return $this->belongsToMany(User::class, 'project_watcher');
    }

    /**
     * Determine if the project is not started.
     *
     * @return bool
     */

    public function isNotStarted()
    {
        // Check if the task has no end date or if the end date is in the future
        // $endDateIsFuture = optional($this->end_date)->isFuture() || $this->end_date === null;

        // Check if the task is not completed
        // $notCompleted = $this->completed_at === null;

        // Check if there are no uncompleted task or subtasks in the task's column
        $allCompleted = $this->columns->flatMap(function ($column) {
            return $column->tasks->flatMap(function ($task) {
                return $task->subTasks()->whereNull('completed_at');
            });
        })->isEmpty();


        // Check if the task is not trashed
        $notTrashed = !$this->trashed();

        // Return true if all conditions are met
        return $allCompleted && $notTrashed;
    }


    /**
     * Determine if the project is ongonig.
     *
     * @return bool
     */
    public function isOngoing()
    {

        // Check if the task has no end date or if the end date is in the future
        $endDateIsFuture = optional($this->end_date)->isFuture() || $this->end_date === null;

        $notTrashed = !$this->trashed();

        // Check if there are any completed subtasks in the project
        $hasCompletedSubtasks = $this->columns()->whereHas('tasks.subtasks', function ($query) {
            $query->whereNotNull('completed_at');
        })->exists();


        // Check if the task is not completed
        $notCompleted = $this->completed_at === null;

        // return $endDateIsFuture && $notCompleted && $notTrashed

        return  $endDateIsFuture && $notCompleted && $notTrashed && $hasCompletedSubtasks;
    }

    /**
     * Determine if the project is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        $overdue = $this->columns()->whereHas('tasks', function ($query) {
            $query->where('due_date', '<', now());
        })->orWhereHas('tasks.subtasks', function ($query) {
            $query->where('due_date', '<', now());
        })->exists();

        return $this->end_date !== null && $overdue &&
            optional($this->end_date)->isPast() &&
            $this->completed_at === null &&
            !$this->trashed();
    }

    /**
     * Determine if the project is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {

        $allCompleted = !$this->columns()->whereHas('tasks.subtasks', function ($query) {
            $query->whereNull('completed_at');
        })->exists();

        return $this->completed_at !== null && $allCompleted && !$this->trashed();
    }

    /**
     * Determine if the project is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->is_approved == 1;
    }

    /**
     * Determine if the project is favorited by the user.
     *
     * @return bool
     */
    public function isFavorite()
    {
        return auth()->user()->favoriteProjects->contains(function ($project) {
            return $project->id === $this->id;
        });
    }

    /**
     * Determine if the project is accessible by authenticated user.
     *
     * @return bool
     */
    public function isAccessible()
    {
        if (auth()->user()->id === $this->user_id || $this->visibility === Project::VISIBILITY_ORGANIZATION) {
            return true;
        }

        return $this->teamMembers->contains(auth()->user()->id);
    }

    /**
     * Determine if the project is watched by the current authenticated user or by the specified user.
     *
     * @param null $user
     * @return bool
     */
    public function isWatched($user = null)
    {
        if ($user === null) {
            $user = auth()->user();
        }

        return $this->watchers()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Mark the project as completed.
     *
     * @return void
     */

    public function markAsCompleted()
    {
        $this->fill([
            'completed_at' => now(),
            'is_approved' => 0
        ])->save();

        event(new ProjectStatusChanged($this));
    }

    /**
     * Mark the project as approved.
     *
     * @return void
     */


    public function markAsApproved()
    {
        $this->fill([
            'is_approved' => 1
        ]);

        event(new ProjectStatusChanged($this));
    }

    /**
     * Mark the project as unapprove.
     *
     * @return void
     */


    public function markAsUnApproved()
    {
        $this->fill([
            'is_approved' => 0
        ]);

        event(new ProjectStatusChanged($this));
    }

    /**
     * Mark the project as incomplete.
     *
     * @return void
     */
    public function markAsIncomplete()
    {
        $this->fill([
            'completed_at' => null,
            'is_approved' => null
        ])->save();

        event(new ProjectStatusChanged($this));
    }

    // public function unreadNotifcation()
    // {
    //     return $this->belongsToMany(User::class, 'activities');

    //     // return $this->;

    // }
}
