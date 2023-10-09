<?php

namespace App\Listeners\Project;

use App\Models\Column;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateColumnTask
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $project_id = $event->id;

        $column = Column::where('project_id', $project_id)->firstOrFail();

        // create task
        $column->tasks()->create([
            'content' => 'Hello',
            'index' => $column->tasks->count()
        ]);
    }
}
