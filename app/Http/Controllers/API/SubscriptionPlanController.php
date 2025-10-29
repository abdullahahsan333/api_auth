<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a list of subscription plans
     */
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return response()->json($plans);
    }

    /**
     * Store a new subscription plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $plan = SubscriptionPlan::create($validated);

        return response()->json([
            'message' => 'Subscription plan created successfully',
            'plan' => $plan,
        ], 201);
    }

    /**
     * Show a specific plan
     */
    public function show($id)
    {
        $plan = SubscriptionPlan::find($id);

        if (! $plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        return response()->json($plan);
    }

    /**
     * Update an existing plan
     */
    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::find($id);

        if (! $plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'price' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|integer|min:1',
        ]);

        $plan->update($validated);

        return response()->json([
            'message' => 'Subscription plan updated successfully',
            'plan' => $plan,
        ]);
    }

    /**
     * Delete a plan
     */
    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);

        if (! $plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $plan->delete();

        return response()->json(['message' => 'Subscription plan deleted successfully']);
    }
}
