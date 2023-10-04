<?php

namespace App\Http\Controllers\Web\Back\App;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('tenant.user');
    }

    /**
     * Show calendar page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $data = [
            'users'  => User::orderBy('name')->get()->map->only(['uuid', 'name', 'avatar_url']),
            'events' => auth()->user()->tasks()
                ->mainTasks()
                ->whereHas('column.project', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->get()
                ->transform(function ($task) {
                    return [
                        'uuid'         => $task->uuid,
                        'title'        => $task->content,
                        'date'         => $task->due_date,
                        'project_uuid' => $task->column->project->uuid,
                        'color'        => $task->column->project->color,
                        'is_completed' => $task->isCompleted(),
                        'classNames'   => $task->isCompleted() ? 'line-through' : '',
                        'user_uuid'    => optional($task->user)->uuid,
                        'priority'     => $task->priority,
                    ];
                }),
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
        ];

        $notification = Activity::where('status', 'unread')->get();

        $notification->map(function ($notice) {
            $notice->datetime = Carbon::parse($notice->created_at)->diffForHumans();
            return $notice;
        });

        $data['user'] = auth()->user()->only(['uuid', 'name', 'avatar_url', 'role', 'email']);

        if (auth()->user()->role !== 3 || auth()->user()->isWatcher) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }


        return Inertia('back/app/calendar/index', $data);
    }
}
