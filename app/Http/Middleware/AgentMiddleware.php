<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'agent') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('agent.login')
                ->withErrors(['email' => 'Unauthorized access.']);
        }

        if (! $user->agent || ! $user->agent->isActive()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('agent.login')
                ->withErrors(['email' => 'Your agent account is pending approval or has been suspended.']);
        }

        return $next($request);
    }
}
