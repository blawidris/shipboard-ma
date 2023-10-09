<?php

namespace App\Events\Project;

use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateDefaultColumn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The newly created project
     *
     * @var \App\Models\Project
     */
    public $project;

    /**
     * Create a new event instance.
     *@var \App\Models\Project
     * @return void
     */

    public function __construct($project)
    {
        $this->project = $project;
    }
}
