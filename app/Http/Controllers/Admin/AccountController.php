<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailChangedNotification;
use App\Mail\EmailChangeVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit()
    {
        return view('admin.account.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:'.config('uploads.max_upload_kb', 20480),
            'language' => 'nullable|string|in:en,fr,de,es',
            'notification_preferences' => 'nullable|array',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        $user->language = $validated['language'] ?? 'en';
        $user->notification_preferences = $validated['notification_preferences'] ?? [];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->save();

        return back()->with('success', 'Account settings updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
            'current_password' => 'required',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password is incorrect.']);
        }

        $token = Str::random(64);
        $user->pending_email = $request->email;
        $user->email_change_token = hash('sha256', $token);
        $user->save();

        $verificationUrl = route('admin.account.verify-email', ['token' => $token]);

        Mail::to($request->email)->send(new EmailChangeVerification($verificationUrl, $user->name));

        return back()->with('success', 'A verification email has been sent to your new email address.');
    }

    public function verifyEmail(Request $request)
    {
        $user = Auth::user();

        if (! $user->pending_email || ! $user->email_change_token) {
            return redirect()->route('admin.account.edit')->with('error', 'No pending email change found.');
        }

        if (hash('sha256', $request->token) !== $user->email_change_token) {
            return redirect()->route('admin.account.edit')->with('error', 'Invalid verification token.');
        }

        $oldEmail = $user->email;
        $newEmail = $user->pending_email;

        $user->email = $newEmail;
        $user->pending_email = null;
        $user->email_change_token = null;
        $user->save();

        Mail::to($oldEmail)->send(new EmailChangedNotification($user->name, $newEmail));

        return redirect()->route('admin.account.edit')->with('success', 'Your email has been updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password is incorrect.']);
        }

        $user->password = $request->password;
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
