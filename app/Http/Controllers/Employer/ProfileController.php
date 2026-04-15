<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        return view('employer.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('employer.profile')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        if ($request->input('step') === 'verify') {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ]);

            return redirect()->route('employer.profile')
                ->with('password_verified', true)
                ->with('success', 'Current password verified. You can now set a new password.');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('employer.profile')->with('success', 'Password changed successfully.');
    }
}
