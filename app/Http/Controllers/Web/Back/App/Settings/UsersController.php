<?php

namespace App\Http\Controllers\Web\Back\App\Settings;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\Settings\NotifyNewMember;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Notification;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('tenant.owner');
        $this->middleware('plan.limit')->only('store', 'update', 'destroy');
    }

    /**
     * Get a listing of all team members.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $data = [
            'users' => User::orderBy('name')->get(),
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
        ];

        $notification = Activity::where('status', 'unread')->orderBy('created_at', 'desc')->get();

        $notification->map(function ($notice) {
            $notice->datetime = Carbon::parse($notice->created_at)->diffForHumans();
            return $notice;
        });

        if (auth()->user()->role !== 3 || auth()->user()->isWatcher) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }

        // dd($data['users']);

        $data['user'] = auth()->user()->only(['uuid', 'name', 'avatar_url', 'role', 'email', 'job_title']);

        return Inertia::render('back/app/settings/users/index', $data);
    }

    /**
     * Store a newly created team member.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'          => ['required', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required'],
            'role'          => ['required'],
            'phone'         => ['required'],
            'department'    => ['required'],
            'job_title'    => ['required'],

        ]);

        $newUser = User::create([
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'password'    => bcrypt($request->input('password')),
            'department'  => $request->input('department'),
            'job_title'  => $request->input('job_title'),
            'phone'  => $request->input('phone'),
            'role'      => $request->input('role') === 'admin' ? User::ROLE_TENANT_OWNER : User::ROLE_TENANT_USER,
            'tenant_id' => auth()->user()->tenant->id,
        ]);

        // send email to newly added team member
        Notification::send($newUser, new NotifyNewMember($request));

        session()->flash('message', __('app.messages.user-created'));

        return back();
    }

    /**
     * Update the specified team member.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name'  => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($request->user, 'uuid')],
            'role'  => ['required'],
            'department' => 'required',
            'job_title' => 'required',
            'phone' => 'required',
        ]);

        $user = User::where('uuid', $request->user)->firstOrFail();

        if ($request->input('password')) {
            $user->update([
                'password' => bcrypt($request->input('password'))
            ]);
        }

        $user->update([
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'department' => $request->input('department'),
            'job_title' => $request->input('job_title'),
            'role'  => $request->input('role') == 'admin' ? User::ROLE_TENANT_OWNER : User::ROLE_TENANT_USER,
        ]);

        session()->flash('message', __('app.messages.user-updated'));

        return back();
    }


    public function roles()
    {
    }

    public function showRole(Request $request)
    {
    }
}
