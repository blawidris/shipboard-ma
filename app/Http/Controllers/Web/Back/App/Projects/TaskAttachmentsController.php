<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Http\Controllers\Controller;
use App\Mail\ProjectTaskMail;
use App\Models\Activity;
use App\Models\Project;
use App\Models\ProjectWatcher;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use MediaUploader;

class TaskAttachmentsController extends Controller
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
     * Get a listing of all task attachments.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $task = Task::where('uuid', $request->task)->firstOrFail();

        $this->authorize('view', $task->column->project);

        return $task->comments->map->media->flatMap(function ($attachment) {
            if ($attachment->count()) {
                return $attachment;
            }
        })->map(function ($attachment) {
            return [
                'uuid'      => $attachment->uuid,
                'filename'  => $attachment->original_filename,
                'extension' => $attachment->extension,
            ];
        });
    }

    /**
     * Store a newly uploaded task attachment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\ConfigurationException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileExistsException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileNotFoundException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileNotSupportedException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileSizeException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\ForbiddenException
     */
    public function store(Request $request)
    {
        $project = Project::where('uuid', $request->project)->firstOrFail();

        $this->authorize('view', $project);

        $media = MediaUploader::fromSource($request->file('file'))
            ->toDestination('local', 'tasks')
            ->onDuplicateIncrement()
            ->useHashForFilename()
            ->beforeSave(function ($model) use ($request) {
                $model->original_filename = $request->file('file')->getClientOriginalName();
                $model->uuid = $request->input('uuid');
                $model->tenant_id = tenant()->id;
            })
            ->upload();

        $activityLog = Activity::create([
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
            'comment' => "New attachment uploaded"
        ]);

        $projectWatcher = ProjectWatcher::with(['user', 'project'])->where('project_id', $project->id)->get();

        if ($projectWatcher) {
            foreach ($projectWatcher as $watcher) {

                Mail::send(new ProjectTaskMail($activityLog, $watcher));
            }
        }

        return response()->json([
            'uuid' => $media->uuid
        ]);
    }
}
