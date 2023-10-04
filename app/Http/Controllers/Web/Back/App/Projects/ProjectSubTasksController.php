<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Http\Controllers\Controller;
use App\Mail\ProjectTaskMail;
use App\Models\Activity;
use App\Models\ProjectWatcher;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectSubTasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('tenant.user');
        $this->middleware('plan.limit')->only('store');
    }

    /**
     * Get a listing of all sub-tasks of the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $task = Task::where('uuid', $request->task)->firstOrFail();

        $this->authorize('view', $task);

        return $task->tasks()->latest()->get()->transform(function ($task) {
            return [
                'uuid'         => $task->uuid,
                'content'      => $task->content,
                'due_date'     => $task->due_date,
                'priority'     => $task->priority,
                'is_completed' => $task->isCompleted(),
                'user'         => [
                    'uuid'       => optional($task->user)->uuid,
                    'name'       => optional($task->user)->name,
                    'avatar_url' => optional($task->user)->avatar_url,
                ],
            ];
        })->values();
    }

    /**
     * Store a new sub-task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => ['required', 'max:255'],
        ]);

        $task = Task::with('column')->where('uuid', $request->task)->firstOrFail();

        $this->authorize('create', [Task::class, $task->column->project]);

        $subTask = Task::create([
            'content' => $request->input('content'),
            'task_id' => $task->id,
        ]);

        $activityLog = Activity::create([
            'project_id' => $task->column->project_id,
            'user_id' => $request->user()->id,
            'comment' => "A subtask has been created - {$subTask->content}"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $task->column->project_id)->get();

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {

                Mail::send(new ProjectTaskMail($activityLog, $watcher));
            }
        }

        return back();
    }
}