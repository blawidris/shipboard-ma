<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectTaskMail extends Mailable
{
    use Queueable, SerializesModels;
    public $log;
    public $watcher;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($log, $watcher)
    {
        $this->log = $log;
        $this->watcher = $watcher;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->watcher->user->email)->subject('Project Task Activity Notification')->markdown('email.task_notification')->with(['activity' => $this->log, 'watcher' => $this->watcher]);
    }
}
