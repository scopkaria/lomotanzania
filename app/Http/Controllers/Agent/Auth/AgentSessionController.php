<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AgentSessionController extends Controller
{
    public function create(): View
    {
        return view('agent.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        // If admin logs in here, redirect to admin panel
        if ($user->isAdmin()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('agent.login')
                ->withErrors(['email' => 'Please use the admin login.']);
        }

        if (! $user->isAgent()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('agent.login')
                ->withErrors(['email' => 'This account is not registered as an agent.']);
        }

        if (! $user->agent || ! $user->agent->isActive()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('agent.login')
                ->withErrors(['email' => 'Your agent account is pending approval or has been suspended.']);
        }

        $request->session()->regenerate();

        return redirect()->route('agent.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('agent.login');
    }
}
