<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSettingsController extends Controller
{
    public function profile()
    {
        $admin = request()->user();

        return view('admin.settings.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($admin->id),
            ],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->id)],
        ]);

        $admin->update([
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
        ]);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['old_password'], $admin->password)) {
            return back()
                ->withErrors(['old_password' => 'Old password is incorrect.'])
                ->withInput();
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully.');
    }
}
