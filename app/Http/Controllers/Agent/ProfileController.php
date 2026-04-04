<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit()
    {
        $agent = Auth::user()->agent;
        return view('agent.profile', compact('agent'));
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $agent = $user->agent;

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'country'      => ['nullable', 'string', 'max:100'],
        ]);

        $user->update(['name' => $validated['name']]);
        $agent->update([
            'company_name' => $validated['company_name'],
            'phone'        => $validated['phone'],
            'country'      => $validated['country'],
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
