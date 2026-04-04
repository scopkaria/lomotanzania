<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariPlan;

class SafariPlanController extends Controller
{
    public function index()
    {
        $plans = SafariPlan::with('safariPackage')
            ->latest()
            ->paginate(20);

        return view('admin.safari-plans.index', compact('plans'));
    }

    public function show(SafariPlan $safariPlan)
    {
        $safariPlan->load('safariPackage');

        return view('admin.safari-plans.show', compact('safariPlan'));
    }

    public function destroy(SafariPlan $safariPlan)
    {
        $safariPlan->delete();

        return redirect()->route('admin.safari-plans.index')
            ->with('success', 'Safari plan deleted successfully.');
    }
}
