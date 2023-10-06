<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Events\Task\TaskDeadlineChanged;
use App\Http\Controllers\Controller;
use App\Mail\ProjectNotification;
use App\Mail\ProjectTaskMail;
use App\Models\Activity;
use App\Models\Column;
use App\Models\ProjectWatcher;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectTasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('tenant.user');
        $this->middleware('plan.limit')->only('store', 'update');
    }

    /**
     * Store a newly created task.
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

        $column = Column::where('uuid', $request->column)->firstOrFail();

        $this->authorize('create', [Task::class, $column->project]);

        $task = $column->tasks()->create([
            'content' => $request->input('content'),
            'index'   => $column->tasks->count(),
        ]);

        $activityLog = Activity::create([
            'project_id' => $column->project_id,
            'user_id' => auth()->user()->id,
            'comment' => auth()->user()->name . " created a new task"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $column->project_id)->get();

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {
                Mail::send(new ProjectTaskMail($activityLog, $watcher));
            }
        }

        return back();
    }

    /**
     * Update the specified task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'content' => ['required', 'max:255'],
        ]);

        $task = Task::with('column')->where('uuid', $request->task)->firstOrFail();

        $this->authorize('update', $task);

        $task->update([
            'content'  => $request->input('content'),
            'due_date' => $request->input('due_date'),
            'priority' => $request->input('priority'),
        ]);



        if ($task->wasChanged('due_date')) {
            event(new TaskDeadlineChanged($task));
        }

        $this->updateAssignedTaskUser($task, $request);

        $this->updateTaskStatus($task, $request);

        return back();
    }


    /**
     * Approve the specified task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function approve(Request $request)
    {

        $task = Task::with('column')->where('uuid', $request->task)->firstOrFail();

        $this->authorize('update', $task);

        if (auth()->user()->role !== 2) {
            return session()->flash('message', 'You are not authorize to approve this project');
        }

        $task->update([
            'is_approved' => 1
        ]);

        $activityLog = Activity::create([
            'project_id' => $task->column->project_id,
            'user_id' => auth()->user()->id,
            'comment' => auth()->user()->name . " has approved $task->content task"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $task->column->project_id)->get();

        // if ($projectWatcher) {
        //     foreach ($projectWatcher as $watcher) {
        //         Mail::send(new ProjectTaskMail($activityLog, $watcher));
        //     }
        // }

        return back();
    }

    /**
     * Unapprove the specified task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */


    public function unapprove(Request $request)
    {

        $task = Task::with('column')->where('uuid', $request->task)->firstOrFail();

        $this->authorize('update', $task);

        $task->update([
            'is_approved' => 0
        ]);

        $activityLog = Activity::create([
            'project_id' => $task->column->project_id,
            'user_id' => auth()->user()->id,
            'comment' =>  auth()->user()->name . "  has approved  $task->content task "
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $task->column->project_id)->get();

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {

                $activityLog->update(
                    [
                        'user_id' => $watcher->user->id
                    ]
                );
                Mail::send(new ProjectTaskMail($activityLog, $watcher));
            }
        }

        return back();
    }

    /**
     * Delete the specified task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $task = Task::with('column')->where('uuid', $request->task)->firstOrFail();

        $this->authorize('delete', $task);

        $task->delete();

        // $activityLog = Activity::create([
        //     'project_id' => $task->column->project_id,
        //     'user_id' => auth()->user()->id,
        //     'comment' => auth()->user()->name . " has deleted $task->content task"
        // ]);

        // $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $task->column->project_id)->get();

        // if ($projectWatcher) {
        //     foreach ($projectWatcher as $watcher) {
        //         Mail::send(new ProjectTaskMail($activityLog, $watcher));
        //     }
        // }

        return back();
    }

    /**
     * Set or unset assigned task user.
     *
     * @param \App\Models\Task $task
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function updateAssignedTaskUser($task, $request)
    {

        if ($request->input('user_uuid')) {

            $assignedTo = $task->assignTo(
                User::where('uuid', $request->input('user_uuid'))->firstOrFail()
            );

            // $activityLog = Activity::create([
            //     'project_id' => $task->column->project_id,
            //     'user_id' => auth()->user()->id,
            //     'comment' => "New user has been assign to {$task->content} task"
            // ]);

            // $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $task->column->project_id)->get();

            // if ($projectWatcher) {
            //     foreach ($projectWatcher as $watcher) {
            //         Mail::send(new ProjectTaskMail($activityLog, $watcher));
            //     }
            // }

            return $assignedTo;
        }




        return $task->unassignUser();
    }

    /**
     * Mark task as completed or incomplete.
     *
     * @param \App\Models\Task $task
     * @param \App\Http\Request $request
     * @return boolean
     */
    protected function updateTaskStatus($task, $request)
    {
        if ($request->input('is_completed')) {

            // if (auth()->user()->role === 1) {

            //     $activityLog = Activity::create([
            //         'project_id' => $task->column->project_id,
            //         'user_id' => auth()->user()->id,
            //         'comment' => auth()->user()->name . " has completed $task->content task"
            //     ]);
            // }

            // $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $task->column->project_id)->get();

            // if ($projectWatcher) {
            //     foreach ($projectWatcher as $watcher) {
            //         Mail::send(new ProjectTaskMail($activityLog, $watcher));
            //     }
            // }
            return $task->markAsCompleted();
        }



        return $task->markAsIncompleted();
    }
}
