<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Http\Controllers\Controller;
use App\Mail\ProjectTaskMail;
use App\Models\Activity;
use App\Models\ProjectWatcher;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
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

        // dd($task->subTasks()->latest());

        $this->authorize('view', $task);

        $data = $task->subTasks()->latest()->get()->transform(function ($task) {
            return [
                'uuid'         => $task->uuid,
                'content'      => $task->content,
                'due_date'     => $task->due_date,
                'priority'     => $task->priority,
                'is_completed' => $task->isCompleted(),
                'is_not_applicable' => $task->is_not_applicable,
                'user'         => [
                    'uuid'       => optional($task->user)->uuid,
                    'name'       => optional($task->user)->name,
                    'avatar_url' => optional($task->user)->avatar_url,
                ],
            ];
        })->values();

        // dd($data);

        return $data;
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

        $subTask = $task->subTasks()->create([
            'content' => $request->input('content'),
            'task_id' => $task->id,
        ]);
        // $subTask = Task::create([
        // 'content' => $request->input('content'),
        // 'task_id' => $task->id,
        // 'column_id' => $task->column->id
        // ]);

        $activityLog = Activity::create([
            'project_id' => $task->column->project_id,
            'user_id' => $request->user()->id,
            'task_id' => $task->id,
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

    public function update(Request $request)
    {
        $subtask = Subtask::with('task')->where('uuid', $request->task)->firstOrFail();

        // dd($task);

        $this->authorize('create', [Task::class, $subtask->task->column->project->id]);

        $subtask->update([
            'content'  => $request->input('content'),
            'due_date' => $request->input('due_date'),
        ]);

        // dd($request->is_not_applicable);

        $this->updateAssignedTaskUser($subtask, $request);

        $this->updateSubTaskStatus($subtask, $request);

        $this->updateIsApplicable($subtask, $request);

        return back();
    }


    /**
     * Set or unset assigned task user.
     *
     * @param \App\Models\Task $task
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function updateAssignedTaskUser($subtask, $request)
    {

        if ($request->input('user_uuid')) {

            // dd($request->input('user_uuid'));

            $user = User::where('uuid', $request->input('user_uuid'))->firstOrFail();

            // dd($subtask);

            $assignedTo = $subtask->assignTo($user);

            $activity = [
                'project_id' => $subtask->task->column->project_id,
                'user_id' => auth()->user()->id,
                'task_id' => $subtask->task->id,
                'comment' => "New user has been assign to a task todo"
            ];

            $projectWatcher = ProjectWatcher::query()->with(['user', 'project']);


            if ($subtask->task->column_id) {
                $activity['project_id'] = $subtask->task->column->project_id;

                $projectWatcher->where('project_id', $subtask->task->column->project_id);
            }

            $projectWatcher->get();

            $activityLog = Activity::create($activity);

            if ($projectWatcher) {
                foreach ($projectWatcher as $watcher) {
                    Mail::send(new ProjectTaskMail($activityLog, $watcher));
                }
            }

            return $assignedTo;
        }

        return $subtask->unassignUser();
    }

    protected function updateIsApplicable($subTask, $request)
    {
        if ($request->input('is_not_applicable')) {
            $updateNotAp =  $subTask->update([
                'is_not_applicable' => true
            ]);

            return $updateNotAp;
        }

        return $updateNotAp =  $subTask->update([
            'is_not_applicable' => false
        ]);
    }
    protected function updateSubTaskStatus($subtask, $request)
    {

        if ($request->input('is_completed')) {

            $activityLog = Activity::create([
                'project_id' => $subtask->task->column->project_id,
                'task_id' => $subtask->task->id,
                'user_id' => auth()->user()->id,
                'comment' => auth()->user()->name . " has completed $subtask->content task"
            ]);


            $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $subtask->task->column->project_id)->get();

            if ($projectWatcher) {
                foreach ($projectWatcher as $watcher) {
                    Mail::send(new ProjectTaskMail($activityLog, $watcher));
                }
            }


            $isCompleted = $subtask->markAsCompleted();

            $this->moveTask($subtask->task->id);

            return $isCompleted;
        }

        $inCompleted = $subtask->markAsIncompleted();
        $this->revertMovedTask($subtask->task->id);

        return $inCompleted;
    }

    protected function moveTask($task)
    {

        // get task
        $task = Task::with('column')->where('id', $task)->firstOrFail();

        // list of subtask;
        $totalTaskSubTask = $task->subTasks()->count();

        // Check if all subtasks of the task are completed
        $completedSubTasks = $task->subTasks()->completed()->count();

        // Get the maximum column_id of the project's columns
        $completedProjectColumnId = $task->column->project->columns->max('id') - 1; #completed column

        if ($completedSubTasks === $totalTaskSubTask && $task->column_id < $completedProjectColumnId) {
            $task->update(['column_id' => $task->column_id + 1]);
            $task->markAsCompleted();
        }

        if ($completedSubTasks === 1) {
            $task->update(['column_id' => $task->column_id + 1]);
        }
    }


    protected function revertMovedTask($taskId)
    {
        // Get the task by ID
        $task = Task::with('column')->where('id', $taskId)->firstOrFail();

        // Count the total number of subtasks for the task
        $totalTaskSubTasks = $task->subTasks()->count();

        // Count the completed subtasks
        $completedSubTasks = $task->subTasks()->completed()->count();


        // Get the maximum column_id of the project's columns
        $completedProjectColumnId = $task->column->project->columns->max('id') - 1;
        $minProjectColumnId = $task->column->project->columns->min('id');

        // dd($completedProjectColumnId);

        // move back to in-progress
        if (!$task->isCompleted() && $task->column_id === $completedProjectColumnId && $totalTaskSubTasks === $completedSubTasks) {
            $task->update(['column_id' => $task->column_id - 1, 'is_approved' => null]);
            // $task->markAsInCompleted();
        }

        if ($completedSubTasks === 0) {
            $task->update(['column_id' => $minProjectColumnId]);
        }
    }
}
