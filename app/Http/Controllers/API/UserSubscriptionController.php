<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserSubscriptionController extends Controller
{
    /**
     * Subscribe user to a plan
     */
    public function subscribe(SubscribeRequest $request)
    {
        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Simulate payment (always success)
        $start = Carbon::now();
        $end = $start->copy()->addDays($plan->duration);

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'active',
        ]);

        return encrypt_response([
            'message' => 'Subscription successful',
            'subscription' => $subscription->load('plan')
        ]);
    }

    /**
     * Get user's active subscriptions
     */
    public function mySubscriptions()
    {
        $user = auth()->user();
        $subs = UserSubscription::with('plan')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return encrypt_response($subs);
    }
}
