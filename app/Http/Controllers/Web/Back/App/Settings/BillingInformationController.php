<?php

namespace App\Http\Controllers\Web\Back\App\Settings;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Support\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class BillingInformationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('tenant.owner');
    }

    /**
     * Show edit billing information page.
     *
     * @return \Inertia\Response
     */
    public function edit()
    {

        $notification = Activity::where('status', 'unread')->orderBy('created_at', 'desc')->get();

        $notification->map(function ($notice) {
            $notice->datetime = Carbon::parse($notice->created_at)->diffForHumans();
            return $notice;
        });


        $data = [
            'countries' => Country::all(),
            'tenant'    => [
                'name'          => tenant()->name,
                'phone'         => tenant()->phone,
                'address_line1' => tenant()->address_line1,
                'address_line2' => tenant()->address_line2,
                'city'          => tenant()->city,
                'country'       => tenant()->country,
                'state'         => tenant()->state,
                'postal_code'   => tenant()->postal_code,
            ],
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
        ];

        if (auth()->user()->role !== 2 || auth()->user()->isWatcher) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }

        $data['user'] = auth()->user()->only(['uuid', 'name', 'avatar_url', 'role', 'email']);

        return Inertia::render('back/app/settings/billing-information/edit', $data);
    }

    /**
     * Update the billing information of the tenant.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        tenant()->update([
            'name'          => $request->input('name'),
            'phone'         => $request->input('phone'),
            'address_line1' => $request->input('address_line1'),
            'address_line2' => $request->input('address_line2'),
            'city'          => $request->input('city'),
            'country'       => $request->input('country'),
            'state'         => $request->input('state'),
            'postal_code'   => $request->input('postal_code'),
        ]);

        tenant()->updateCustomer();

        session()->flash('message', __('app.messages.billing-information-updated'));

        return back();
    }
}