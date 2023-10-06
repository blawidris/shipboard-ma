<?php

namespace App\Http\Controllers\Web\Back\App\Settings;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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

        $data['user'] = auth()->user()->only(['uuid', 'name', 'avatar_url', 'role', 'email']);

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

        ]);

        User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => bcrypt($request->input('password')),
            'department'  => bcrypt($request->input('department')),
            'phone'  => bcrypt($request->input('phone')),
            'role'      => $request->input('role') === 'admin' ? User::ROLE_TENANT_OWNER : User::ROLE_TENANT_USER,
            'tenant_id' => auth()->user()->tenant->id,
        ]);

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
