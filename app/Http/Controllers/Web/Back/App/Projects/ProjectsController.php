<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Events\Project\CreateDefaultColumn;
use App\Events\Project\PredefinedColumnsTemplate;
use App\Events\Project\ProjectAssignedToUser;
use App\Events\Project\ProjectTimelineChanged;
use App\Filters\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Column;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectsController extends Controller
{

    protected $tasks  = [
        [
            'name' => 'Receive Client Brief',
            'todos' => [
                'Receive client brief from email.',
                'Receive client briefs from directors.',
                'Receive client briefs from phone calls or referrals.',
            ],
        ],
        [
            'name' => 'Respond to Client\'s Brief',
            'todos' => [
                'Engage architects for site visits.',
                'Define project stages.',
                'Generate reports and record findings.',
            ],
        ],
        [
            'name' => 'Design Stage',
            'todos' => [
                'Request a meeting with the creative director.',
                'Receive design direction from the creative director.',
                'Create initial design drafts.',
                'Share designs with the creative director for review.',
                'Implement changes based on the creative director\'s feedback.',
                'Share the revised design with the client.',
            ],
        ],
        [
            'name' => 'Client Design Review and Sign-Off',
            'todos' => [
                'Client reviews and suggests design changes.',
                'The client approves the final design.',
            ],
        ],
        [
            'name' => 'Quotation Stage',
            'todos' => [
                'Break down the quotation process.',
                'Estimate material costs and availability.',
                'Include design costs in the quotation.',
                'Prepare a quotation sheet for approval with various markups.',
                'Get approved markup percentage from BDD.',
                'Send the quotation to the client.',
                'Negotiate the quotation with the client.',
                'Obtain final client sign-off on the quotation.',
                'Request funds from the client for the approved quotation.',
            ],
        ],
        [
            'name' => 'Purchase Order (PO) Processing',
            'todos' => [
                'If working with blue-chip clients on a PO basis, request and expect the PO.',
            ],
        ],
        [
            'name' => 'Production Stage',
            'todos' => [
                'Architects to develop production drawings.',
                'Ensure production drawings include all dimensions.',
                'Send production drawings to Creative Director for approvals/adjustments.',
                'Plan for drainage, if needed.',
            ],
        ],
        [
            'name' => 'Audit and Cost Review',
            'todos' => [
                'Audit all project costs.',
                'Review and verify project costs.',
                'Audit to Conduct negotiations with various vendors.',
                'Audit to send budgets in for approval.',
                'Audit to Communicate negotiated and approved prices to procurement/pass budget to accounts.',
            ],
        ],
        [
            'name' => 'Procurement',
            'todos' => [
                'Receive the list of items from the audit.',
                'Categorize items based on suppliers and project categories.',
                'Allocate costs to each vendor.',
                'Collect account details from vendors.',
                'Cross-check items available in the store against the project requirements.',
                'Transfer items from the store to accounts.',
                'Confirm and eliminate any item duplication.',
                'Request and obtain approvals for procurement decisions.',
            ],
        ],
        [
            'name' => 'Accounts',
            'todos' => [
                'Verify and confirm items received from procurement.',
                'Check for item duplications.',
                'Upload verified items and project details.',
                'Request and obtain necessary approvals at this stage.',
            ],
        ],
        [
            'name' => 'Production Phase',
            'todos' => [
                'Confirm materials delivered by procurement.',
                'Allocate production location and workforce.',
                'Identify and hire contractors for specialized work (e.g., carpentry, glasswork).',
                'Break down production stages and requirements.',
                'Monitor progress and meet deadlines.',
                'Conduct quality checks at different production stages.',
                'Gain approvals for different production phases.',
            ],
        ],
        [
            'name' => 'Planning the Installation',
            'todos' => [
                'Plan the timing and schedule for the installation team\'s arrival.',
                'Coordinate with relevant parties to ensure a smooth arrival.',
                'Arrange for the necessary transportation, such as a truck.',
                'Analyze cost estimates for installation materials and labor and raise the installation budget.',
                'Identify and assign qualified personnel for the installation team.',
                'Determine roles and responsibilities within the team.',
                'Create a comprehensive checklist of all items to be moved and installed.',
                'Decide if any drills or additional tools are required for the installation.',
            ],
        ],
        [
            'name' => 'Installation Phase',
            'todos' => [
                'Assemble the installation team, budget, and timelines.',
                'Identify the installation location.',
                'Coordinate the arrival of trucks for materials.',
                'Ensure the checklist is completed with all necessary items.',
                'Confirm that all listed items are loaded onto the truck.',
                'Verify that items leaving the company match the checklist.',
                'Record any items added to or removed from the checklist.',
                'Ensure security measures are in place during loading.',
                'Share a copy of the checklist/loaded items with the driver and security personnel for checks.',
                'Confirm the timeline for truck arrival.',
            ],
        ],
        [
            'name' => 'Transition to Installation Site',
            'todos' => [
                'Confirm that the truck has left with all the items.',
                'Supervise the unloading and delivery of materials at the installation site.',
                'Initiate the installation process at the site.',
                'Document the current state of the site with photographs.',
                'Continuously capture images of the work in progress.',
                'Seek approval or guidance for resolving any issues encountered.',
                'Continue with the installation process.',
                'Quality Control and Monitoring:',
                'Conduct quality control checks during and after installation.',
                'Document any scratches, tears, gaps, or imperfections.',
                'Share images with management for resolution directions.',
            ],
        ],
        [
            'name' => 'Completion and Client Sign-Off',
            'todos' => [
                'Sign off on each installation step.',
                'Verify the location\'s finish and quality of work.',
                'Obtain client sign-off on the completed installation.',
                'Ensure that sign-off is communicated to the accounts department.',
            ],
        ],
        [
            'name' => 'Financial Closing',
            'todos' => [
                'Receive the balance payment from the client for the installation services.',
                'Close the project financially.',
                'Document the entire process and project history.',
            ],
        ],
        [
            'name' => 'Project Completion: Documentation and Archiving',
            'todos' => [
                'Archive final designs and installation images in cloud storage for future reference.',
                'Store project-related documents and learning materials in the cloud for easy access.',
                'Finalize the project by uploading all necessary documentation to the appropriate storage location (ABC folder).',
            ],
        ],
    ];

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
     * Show a listing of all projects.
     *
     * @param \App\Filters\ProjectFilter $filters
     * @return \Inertia\Response
     */
    public function index(ProjectFilter $filters)
    {

        $notification = Activity::where('status', 'unread')->get();

        $data = [
            'filters'  => request()->all('visibility', 'status', 'search'),
            'users'    => User::orderBy('name')->get()->map->only(['uuid', 'name', 'email', 'avatar_url', 'role', 'job_title']),
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
            'projects' => Project::with('teamMembers', 'user', 'tasks')
                ->filter($filters)
                ->accessible()
                ->latest()
                ->paginate(10)
                ->transform(function ($project) {
                    return [
                        'uuid'          => $project->uuid,
                        'name'          => $project->name,
                        'description'   => $project->description,
                        'status'        => $project->status,
                        'approved'        => strVal($project->is_approved),
                        'pending_tasks' => $project->tasks()->pending()->where('task_id', null)->count(),
                        'days_left'     => $project->days_left,
                        'team_members'  => $project->teamMembers->transform(function ($user) {
                            return [
                                'name'       => $user->name,
                                'avatar_url' => $user->avatar_url,
                            ];
                        })
                    ];
                })
        ];

        $data['user'] = auth()->user()->only(['uuid', 'name', 'avatar_url', 'role', 'email', 'job_title']);

        if (auth()->user()->role === 2) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }

        return Inertia::render('back/app/projects/index', $data);
    }

    /**
     * Show project page.
     *
     * @param \Illuminate\Http\ Request $request
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request)
    {
        $project = Project::with('columns', 'columns.tasks', 'columns.tasks.user', 'user', 'teamMembers')
            ->where('uuid', $request->project)
            ->withTrashed()
            ->firstOrFail();

        $notification = Activity::where('status', 'unread')->get();

        $notification->map(function ($notice) {
            $notice->datetime = Carbon::parse($notice->created_at)->diffForHumans();
            return $notice;
        });

        $subtaskCount = $project->columns->sortBy('index')->flatMap(function ($column) {
            return $column->tasks()->get()->sortBy('id')->flatmap(function ($task) {
                return $task->subTasks()->get();
            });
        })->count();

        $completeSubtaskCount = $project->columns->sortBy('index')->flatMap(function ($column) {
            return $column->tasks()->get()->sortBy('id')->flatmap(function ($task) {
                return $task->subTasks()->where('completed_at', '<', now())->get();
            });
        })->count();

        // $subtaskCount = $project->tasks()->subTasks()->get()->count();

        // dd(
        //     $completeSubtaskCount
        // );

        $data = [
            'users'   => User::orderBy('id')->get()->map->only(['uuid', 'name', 'email', 'avatar_url', 'role', 'job_title']),
            'can'     => [
                'update_project'  => auth()->user()->can('update', $project),
                'archive_project' => auth()->user()->can('delete', $project),
                'delete_project'  => auth()->user()->can('forceDelete', $project)
            ],
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
            'project' => [
                'uuid'         => $project->uuid,
                'name'         => $project->name,
                'description'  => $project->description,
                'visibility'   => $project->visibility,
                'timeline'     => $project->timeline,
                'color'        => $project->color,
                'status'       => $project->status,
                'team_members' => $project->teamMembers->pluck('uuid'),
                'is_favorite'  => $project->isFavorite(),
                'is_archived'  => $project->trashed(),
                'is_completed' => $project->isCompleted(),
                'is_approved'  => strval($project->isApproved()),
                'is_watched'   => $project->isWatched(),
                'stats'        => [
                    'total_tasks' => $project->tasks()->count(),
                    'open_tasks' => $project->tasks()->inComplete()->count(),
                    'completed_tasks' => $project->tasks()->completed()->count(),
                    'total_subtasks' => $subtaskCount,
                    'completed_subtasks' => $completeSubtaskCount,
                ],
                'columns'      => $project->columns->sortBy('index')->map(function ($column) {
                    return [

                        'uuid'  => $column->uuid,
                        'name'  => $column->name,
                        'index' => $column->index,
                        'tasks' => $column->tasks()->get()->sortBy('id')->transform(function ($task) {
                            // 'tasks' => $column->tasks()->mainTasks()->get()->sortBy('id')->transform(function ($task) {
                            return [
                                'id'             => $task->id,
                                'uuid'           => $task->uuid,
                                'content'        => $task->content,
                                'index'          => $task->index,
                                'due_date'       => optional($task->due_date)->format('Y-m-d'),
                                'start_date'     => optional($task->start_date)->format('Y-m-d'),
                                'status'         => $task->status,
                                'priority'       => $task->priority,
                                'is_completed'   => $task->isCompleted(),
                                'is_approved'    => strval($task->isApproved()),
                                'activity'       => $task->activities()->get()->only(['comment', '']),
                                'user'           => [
                                    'uuid'       => optional($task->user)->uuid,
                                    'name'       => optional($task->user)->name,
                                    'avatar_url' => optional($task->user)->avatar_url,
                                ],
                                'sub_tasks'      => [
                                    // 'total'     => $task->tasks()->subTasks()->count(),
                                    'total'     => $task->subTasks()->count(),
                                    'completed' => $task->subTasks()->completed()->count(),
                                    // 'completed' => $task->tasks()->subTasks()->completed()->count(),
                                ]
                            ];
                        })->values()
                    ];
                })->values()
            ],
            'projects' => $this->getUserProjects(),
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
            'user' => auth()->user()->only(['name', 'email', 'role', 'uuid', 'avatar_url', 'job_title'])
        ];

        if (auth()->user()->role !== 3 || auth()->user()->isWatcher) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }

        $this->authorize('view', $project);

        return Inertia::render('back/app/projects/show', $data);
    }

    /**
     * Store a new created project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'visibility'  => ['required'],
            'color'       => ['required'],
        ]);

        $project = Project::create([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'color'       => $request->input('color'),
            'visibility'  => $request->input('visibility'),
            'user_id'     => auth()->user()->id,
            'tenant_id'   => tenant()->id,
        ]);

        // create project column
        $this->createColumn($project);

        // CreateDefaultColumn::dispatch($project);
        $this->updateProjectTimeline($project, $request);

        $this->syncProjectTeamMembers($project, $request);


        session()->flash('message', __('app.messages.project-created'));

        return back();
    }

    /**
     * Update an existing project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name'        => ['required', 'string'],
            'description' => ['required', 'string'],
            'visibility'  => ['required'],
            'color'       => ['required']
        ]);

        $project = Project::where('uuid', $request->project)->firstOrFail();

        $this->authorize('update', $project);

        $project->update([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'color'       => $request->input('color'),
            'visibility'  => $request->input('visibility'),
        ]);

        $this->updateProjectTimeline($project, $request);

        $this->syncProjectTeamMembers($project, $request);

        session()->flash('message', __('app.messages.project-updated'));

        return back();
    }

    /**
     * Delete an existing project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $project = Project::withTrashed()->where('uuid', $request->project)->firstOrFail();

        if ($project->trashed()) {
            $this->authorize('forceDelete', $project);
            $project->forceDelete();
            session()->flash('message', __('app.messages.project-deleted'));
        } else {
            $this->authorize('delete', $project);
            $project->delete();
            session()->flash('message', __('app.messages.project-archived'));
        }

        return redirect()->route('app:projects.index');
    }

    /**
     * Restore an archived project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore(Request $request)
    {
        $project = Project::where('uuid', $request->project)->onlyTrashed()->firstOrFail();

        $this->authorize('restore', $project);

        $project->restore();

        session()->flash('message', __('app.messages.project-restored'));

        return redirect()->route('app:projects.index');
    }

    /**
     * Sync project team members.
     *
     * @param \App\Models\Project $project
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function syncProjectTeamMembers($project, $request)
    {
        if ($project->visibility !== Project::VISIBILITY_TEAM_MEMBERS) {
            $project->teamMembers()->sync([]);

            return;
        }

        if ($request->input('team_members') === null) {
            return $project->teamMembers()->sync([]);;
        }

        $oldTeamMembers = $project->teamMembers;

        $newTeamMembers = User::whereIn('uuid', $request->input('team_members'))->get();
        $project->teamMembers()->sync($newTeamMembers->pluck('id'));

        $project->refresh();

        collect($newTeamMembers->whereNotIn('id', $oldTeamMembers->pluck('id')->toArray())->all())
            ->pipe(function ($newTeamMembers) use ($project) {
                if ($newTeamMembers->count()) {
                    event(new ProjectAssignedToUser($newTeamMembers, $project));
                }
            });
    }

    /**
     * Update the start and end dates of the project.
     *
     * @param \App\Models\Project $project
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function updateProjectTimeline($project, $request)
    {
        if ($request->input('timeline') !== null) {
            $period = Carbon::period($request->input('timeline'));

            $project->update([
                'start_date' => optional($period)->start,
                'end_date'   => optional($period)->end,
            ]);
        } else {
            $project->update([
                'start_date' => null,
                'end_date'   => null,
            ]);
        }

        if ($project->wasChanged('start_date') || $project->wasChanged('end_date')) {
            event(new ProjectTimelineChanged($project));
        }
    }


    protected function getUserProjects()
    {

        return Project::with('user', 'teamMembers')
            ->where('user_id', auth()->user()->id)
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

    protected function createColumn($project)
    {

        $columns = ['not started', 'in progress', 'review', 'completed', 'overdue'];

        foreach ($columns as $column) {
            $project->columns()->create([
                'name' => $column,
                'index' => $project->columns->count()
            ]);
        }

        $column = $project->columns()->first();

        // create task
        $this->createColumnTask($column);
    }


    protected function createColumnTask($column)
    {
        $column = Column::where('uuid', $column->uuid)->firstorFail();

        foreach ($this->tasks as $key => $task) {
            $tas = $column->tasks()->create([
                'content' => $task['name'],
                'index' => $column->tasks->count()
            ]);

            $task['uuid'] = $tas->uuid;
            $task['column_id'] = $tas->column_id;
            $task['id'] = $tas->id;

            // create subtask
            $this->createColumnTaskSubTask($task);
        }
    }

    protected function createColumnTaskSubTask($task)
    {


        $tas = Task::with('column')->where('uuid', $task['uuid'])->firstOrFail();

        foreach ($task['todos'] as $subTask) {
            $tas->subtasks()->create([
                'content' => $subTask,
                'task_id' => $task['id'],
                'column_id' => $task['column_id']
            ]);
        }
    }
}