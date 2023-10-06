<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Http\Controllers\Controller;
use App\Mail\ProjectNotification;
use App\Models\Activity;
use App\Models\Column;
use App\Models\Project;
use App\Models\ProjectWatcher;
// use App\Models\ProjectWatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectColumnsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('tenant.user');
        $this->middleware('plan.limit')->only('store', 'update', 'destroy');
    }

    /**
     * Store a new created column.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:255'],
        ]);


        $project = Project::where('uuid', $request->project)->firstOrFail();

        $this->authorize('view', $project);



        $column = $project->columns()->create([
            'name'  => $request->input('name'),
            'index' => $project->columns->count(),
        ]);


        // dd($column);

        $activityLog = Activity::create([
            'project_id' => $project->id,
            'user_id' => auth()->user()->id,
            'comment' => auth()->user()->name . " has  created new column - {$column->name}"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $project->id)->get();

        // dd($projectWatcher);

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {
                $activityLog->update([
                    'user_id' => $watcher->user->id
                ]);
                // dd($watcher->user->email);
                Mail::send(new ProjectNotification($activityLog, $watcher));
            }
        }
        return back();
    }

    /**
     * Update an existing project column.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:255'],
        ]);

        $column = Column::where('uuid', $request->column)->firstOrFail();

        $this->authorize('view', $column->project);

        $column->update([
            'name' => $request->input('name'),
        ]);

        // dd($column);

        $activityLog = Activity::create([
            'project_id' => $column->project_id,
            'user_id' => auth()->user()->id,
            'comment' => auth()->user()->name . " has updated {$column->name} column"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $column->project_id)->get();

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {
                Mail::send(new ProjectNotification($activityLog, $watcher));
            }
        }

        return back();
    }

    /**
     * Delete an existing project column.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $column = Column::where('uuid', $request->column)->firstOrFail();

        $this->authorize('view', $column->project);

        $column->delete();

        // dd($column);

        $activityLog = Activity::create([
            'project_id' => $column->project_id,

            'user_id' => auth()->user()->id,
            'comment' => auth()->user()->name . " has deleted a column"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $column->project_id)->get();

        // dd($projectWatcher);

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {
                // dd($watcher->user->email);
                Mail::send(new ProjectNotification($activityLog, $watcher));
            }
        }

        return back();
    }
}
