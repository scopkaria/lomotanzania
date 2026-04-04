<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredAgentController extends Controller
{
    public function create(): View
    {
        return view('agent.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'country'      => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'agent',
        ]);

        Agent::create([
            'user_id'      => $user->id,
            'company_name' => $request->company_name,
            'phone'        => $request->phone,
            'country'      => $request->country,
            'status'       => 'pending',
        ]);

        return redirect()->route('agent.login')
            ->with('success', 'Registration successful! Your account is pending admin approval.');
    }
}
