<?php

namespace App\Http\Controllers\Web\Back\App\Settings;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
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
     * Show manage subscription page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $subscription = tenant()->defaultSubscription();

        $notification = Activity::where('status', 'unread')->orderBy('created_at', 'desc')->get();

        $notification->map(function ($notice) {
            $notice->datetime = Carbon::parse($notice->created_at)->diffForHumans();
            return $notice;
        });


        $data = [
            'plans'                  => $this->getAvailablePlans(),
            'token'                  => tenant()->generateClientToken(),
            'paymentMethods'         => tenant()->paymentMethods(),
            'gateway'                => config('billing.gateway'),
            'stripe_publishable_key' => config('billing.drivers.stripe.publishable'),
            'subscription'           => [
                'incomplete'     => $subscription && $subscription->isIncomplete(),
                'ends_at'        => $subscription && $subscription->valid() ? optional($subscription->ends_at)->format('Y-m-d') : null,
                'on_trial'       => $subscription && $subscription->onTrial(),
                'latest_payment' => [
                    'client_secret' => $subscription && $subscription->isIncomplete() ? $subscription->latestPayment()->client_secret : null,
                    'amount'        => $subscription && $subscription->isIncomplete() ? $subscription->latestPayment()->amount : null,
                ],
                'plan'           => [
                    'id'   => $subscription && $subscription->valid() ? $subscription->plan->id : null,
                    'name' => $subscription && $subscription->valid() ? $subscription->plan->name : null,
                ]
            ],
            'notification'        => [
                'total_count' => 0,
                'list' => []
            ],
        ];

        $data['user'] = auth()->user()->only(['uuid', 'name', 'avatar_url', 'role', 'email', 'job_title']);

        if (auth()->user()->role !== 3 || auth()->user()->isWatcher) {
            $data['notification'] = [
                'total_count' => $notification->count(),
                'list' => $notification
            ];
        }

        return Inertia::render('back/app/settings/subscription/index', $data);
    }

    /**
     * Store new subscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required',
        ]);

        $plan = Plan::where('is_default', false)
            ->where('status', Plan::STATUS_ACTIVE)
            ->findOrFail($request->input('plan'));

        if (tenant()->hasActiveSubscription()) {
            $subscription = tenant()->defaultSubscription();
            $subscription->swap($plan);
        } else {
            tenant()->subscribe($plan);
        }

        session()->flash('message', __('app.messages.subscription-updated'));

        return back();
    }

    /**
     * Cancel current active subscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        tenant()->defaultSubscription()->cancel();

        session()->flash('message', __('app.messages.subscription-canceled'));

        return back();
    }

    /**
     * Get available plans.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAvailablePlans()
    {
        return Plan::where('status', Plan::STATUS_ACTIVE)
            ->where('is_default', false)
            ->orderBy('price')
            ->get(['id', 'name', 'price']);
    }
}