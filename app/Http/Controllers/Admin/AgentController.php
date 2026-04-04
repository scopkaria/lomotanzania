<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        // Stats for Dashboard tab
        $stats = [
            'total'      => Agent::count(),
            'active'     => Agent::where('status', 'active')->count(),
            'suspended'  => Agent::where('status', 'suspended')->count(),
            'banned'     => Agent::where('status', 'banned')->count(),
            'pending'    => Agent::where('status', 'pending')->count(),
            'bookings'   => Booking::count(),
            'commission' => Booking::whereIn('status', ['pending', 'confirmed'])->sum('commission_amount'),
        ];

        $pendingAgents = Agent::with('user')
            ->where('status', 'pending')
            ->withCount('bookings')
            ->latest()
            ->get();

        $activeAgents = Agent::with('user')
            ->withCount('bookings')
            ->withSum('bookings', 'commission_amount')
            ->where('status', 'active')
            ->latest()
            ->paginate(20, ['*'], 'active_page');

        $suspendedAgents = Agent::with('user')
            ->withCount('bookings')
            ->withSum('bookings', 'commission_amount')
            ->whereIn('status', ['suspended', 'banned'])
            ->latest()
            ->paginate(20, ['*'], 'suspended_page');

        $defaultCommission = 10;
        $setting = Setting::firstOrCreate([], ['site_name' => 'Lomo Tanzania Safari']);

        return view('admin.agents.index', compact(
            'stats', 'pendingAgents', 'activeAgents', 'suspendedAgents', 'defaultCommission', 'setting'
        ));
    }

    public function show(Agent $agent)
    {
        $agent->load(['user', 'bookings.safari', 'safariRequests.response']);

        $earningStats = [
            'total'     => $agent->bookings()->whereIn('status', ['pending', 'confirmed'])->sum('commission_amount'),
            'confirmed' => $agent->bookings()->where('status', 'confirmed')->sum('commission_amount'),
            'pending'   => $agent->bookings()->where('status', 'pending')->sum('commission_amount'),
            'revenue'   => $agent->bookings()->whereIn('status', ['pending', 'confirmed'])->sum('total_price'),
        ];

        return view('admin.agents.show', compact('agent', 'earningStats'));
    }

    public function approve(Agent $agent)
    {
        $agent->update(['status' => 'active']);
        return back()->with('success', "{$agent->user->name} has been approved and can now log in.");
    }

    public function suspend(Agent $agent)
    {
        $agent->update(['status' => 'suspended']);
        return back()->with('success', "{$agent->user->name} has been suspended.");
    }

    public function ban(Agent $agent)
    {
        $agent->update(['status' => 'banned']);
        return back()->with('success', "{$agent->user->name} has been banned.");
    }

    public function restore(Agent $agent)
    {
        $agent->update(['status' => 'active']);
        return back()->with('success', "{$agent->user->name} has been restored to active.");
    }

    public function reject(Agent $agent)
    {
        $agent->update(['status' => 'suspended']);
        return redirect()->route('admin.agents.index')->with('success', "{$agent->user->name} was rejected.");
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'status'          => ['required', 'in:pending,active,suspended,banned'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $agent->update($validated);

        return back()->with('success', 'Agent settings updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        $agent->user->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted.');
    }
}
