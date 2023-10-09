<?php

namespace App\Listeners\Project;

use App\Events\CreateDefaultColumn;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ColumnTableTemplate
{

    /**
     * Handle the event.
     *
     * @param  CreateDefaultColumn  $event
     * @return void
     */
    public function handle($event)
    {
        $project = Project::where('id', $event->id)->firstOrFail();

        // create Column
        $column = $project->columns()->create([
            'name' => 'Not started',
            'index' => $project->columns->count()
        ]);

        //
    }
}
