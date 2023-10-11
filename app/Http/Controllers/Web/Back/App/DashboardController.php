<?php

namespace App\Http\Controllers\Web\Back\App;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use App\Models\ProjectWatcher;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Request;

class DashboardController extends Controller
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
     * Show the application dashboard page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $tasks = auth()->user()->tasks;
        $completedTasksByMonth = $this->getCompletedTasksByMonth();
        $completedTasksByDay = $this->getCompletedTasksByDay();
        $notification = Activity::where('status', 'unread')->orderBy('created_at', 'desc')->get();
        // $projects = $this->getUserProjects();

        $notification->map(function ($notice) {
            $notice->datetime = Carbon::parse($notice->created_at)->diffForHumans();
            return $notice;
        });

        $data = [
            'stats'                 => [
                'projects'        => Project::accessible()->count(),
                'total_tasks'     => $tasks->count(),
                'open_tasks'      => $tasks->where('completed_at', null)->count(),
                'completed_tasks' => $tasks->where('completed_at', '!=', null)->count(),
            ],
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
            // 'projects' => $projects,
            'tasks_by_month'        => $completedTasksByMonth,
            'tasks_by_month_count'  => collect($completedTasksByMonth)->sum(),
            'tasks_by_day'          => $completedTasksByDay,
            'tasks_by_day_count'    => collect($completedTasksByDay)->sum(),
            'most_productive_month' => month_from_int($completedTasksByMonth->search($completedTasksByMonth->max(), true)),
            'most_productive_day'   => day_from_int($completedTasksByDay->search($completedTasksByDay->max(), true))
        ];


        if (auth()->user()->role === 2 || auth()->user()->isWatcher) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }

        $data['user'] = [
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'name' => auth()->user()->name,
            'avatar_url' => auth()->user()->getAvatarUrlAttribute(),
            'job_title' => auth()->user()->job_title
        ];



        return Inertia::render('back/app/dashboard', $data);
    }

    public function read($id)
    {
        // $activityId = $id;

        $act = Activity::with('project')->where('id', $id)->firstOrFail();

        $actUpdate = $act->update([
            'status' => 'read'
        ]);


        return response()->json(['project_uuid' => $act->project->uuid]);
    }


    /**
     * Get completed tasks by month for the current year.
     *
     * @return array
     */
    protected function getCompletedTasksByMonth()
    {
        $tasks = collect()->pad(12, 0);

        auth()->user()->tasks()
            ->completed()
            ->whereYear('completed_at', date('Y'))
            ->get()
            ->groupBy(function ($task) {
                return $task->completed_at->format('m');
            })
            ->map
            ->count()
            ->each(function ($value, $key) use ($tasks) {
                $tasks->put(intval($key) - 1, $value);
            });

        return $tasks;
    }

    /**
     * Most productive days for current year.
     *
     * @return array
     */
    protected function getCompletedTasksByDay()
    {
        $tasks = collect()->pad(7, 0);

        auth()->user()->tasks()
            ->completed()
            ->get()
            ->groupBy(function ($task) {
                return $task->completed_at->format('w');
            })
            ->map
            ->count()
            ->each(function ($value, $key) use ($tasks) {
                $tasks->put(intval($key), $value);
            });


        return $tasks;
    }

    protected function getUserProjects()
    {
        return Project::where('user_id', auth()->user()->id)
            ->accessible()
            ->get()
            ->transform(function ($project) {
                return [
                    'uuid'  => $project->uuid,
                    'name'  => $project->name,
                    'color' => $project->color,
                ];
            });
    }
}