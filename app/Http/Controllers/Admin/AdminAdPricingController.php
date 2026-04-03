<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPricingRule;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdminAdPricingController extends Controller
{
    /** Show the pricing matrix — all placement × priority combinations. */
    public function index()
    {
        // Load as a keyed collection so the blade can do $rules[$placement][$priority]
        $rules = AdPricingRule::orderBy('placement')->orderBy('priority')->get()
            ->groupBy('placement')
            ->map(fn($group) => $group->keyBy('priority'));

        $placements = Advertisement::PLACEMENTS;
        $priorities = Advertisement::PRIORITIES;

        return view('admin.ad-pricing.index', compact('rules', 'placements', 'priorities'));
    }

    /** Update a single pricing rule (inline from the matrix table). */
    public function update(Request $request, AdPricingRule $adPricingRule)
    {
        $request->validate([
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'min_days'      => ['required', 'integer', 'min:1', 'max:365'],
            'is_active'     => ['boolean'],
        ]);

        $adPricingRule->update([
            'price_per_day' => $request->price_per_day,
            'min_days'      => $request->min_days,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Pricing updated for {$adPricingRule->placementLabel()} — {$adPricingRule->priorityLabel()}.");
    }
}