@component('mail::message')

    Hello {{ $watcher->user->name }},

    There is new activity on the "{{ $watcher->project->name }}" project:

    - **Action**: {{ $activity->comment }}
    - **Date**: {{ $activity->created_at->format('Y-m-d H:i:s') }}

    Thank you for staying updated on this project!

    @component('mail::button', ['url' => route('app:projects.show', ['project' => $watcher->project->uuid])])
        View Project
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
