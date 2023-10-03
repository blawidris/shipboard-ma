<?php

namespace App\Http\Controllers\Web\Back\App\Projects;

use App\Http\Controllers\Controller;
use App\Mail\ProjectTaskMail;
use App\Models\Activity;
use App\Models\ProjectWatcher;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Plank\Mediable\Media;

class TaskCommentsController extends Controller
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
     * Get a listing of all task comments.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $task = Task::where('uuid', $request->task)->firstOrFail();

        $this->authorize('view', $task->column->project);

        return $task->comments->sortByDesc('created_at')->transform(function ($comment) {
            return [
                'uuid'        => $comment->uuid,
                'content'     => $comment->content,
                'created_at'  => $comment->created_at->diffForHumans(),
                'attachments' => $comment->getMedia('attachments')->transform(function ($attachment) {
                    return [
                        'uuid'     => $attachment->uuid,
                        'filename' => $attachment->original_filename,
                        'size'     => $attachment->size,
                    ];
                }),
                'user'        => [
                    'uuid'       => $comment->user->uuid,
                    'name'       => $comment->user->name,
                    'avatar_url' => $comment->user->avatar_url,
                ],
            ];
        })->values();
    }

    /**
     * Store a newly created task comment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $task = Task::where('uuid', $request->task)->firstOrFail();

        $this->authorize('view', $task->column->project);

        $this->validate($request, [
            'content' => 'required',
        ]);

        $comment = $task->comments()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id,
        ]);

        if (count($request->input('attachments'))) {
            Media::whereIn('uuid', $request->input('attachments'))->each(function ($attachment) use ($comment) {
                $comment->attachMedia($attachment, 'attachments');
            });
        }

        $activityLog = Activity::create([
            'project_id' => $task->column->project_id,
            'user_id' => $request->user()->id,
            'comment' => "Comment has been added to {$task->content}"
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
