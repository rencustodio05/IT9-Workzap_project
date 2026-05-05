<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function showProfile()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $isEditMode = false;

        return view('applicant.profile', compact('user', 'isEditMode'));
    }

    public function editProfile()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $isEditMode = true;

        return view('applicant.profile', compact('user', 'isEditMode'));
    }

    public function edit()
    {
        return $this->showProfile();
    }

    public function show()
    {
        return $this->showProfile();
    }

    public function accountSecurity()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        return view('applicant.account-security', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'contact_number' => ['required', 'digits:11'],
            'address' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'desired_job_title' => ['required', 'string', 'max:255'],
            'skills' => ['required', 'string'],
            'work_experience' => ['required', 'string'],
            'education' => ['required', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $nameParts = preg_split('/\s+/', trim($validated['full_name']));
        $firstName = array_shift($nameParts) ?? '';
        $lastName = trim(implode(' ', $nameParts));

        if ($lastName === '') {
            $lastName = $firstName;
        }

        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'desired_job_title' => $validated['desired_job_title'],
            'skills' => $validated['skills'],
            'work_experience' => $validated['work_experience'],
            'education' => $validated['education'],
        ];

        if ($request->hasFile('profile_photo')) {
            if (!empty($user->profile_photo_path)) {
                $oldPath = str_contains($user->profile_photo_path, '/')
                    ? $user->profile_photo_path
                    : 'profile/' . $user->profile_photo_path;

                Storage::disk('public')->delete($oldPath);
            }

            $extension = $request->file('profile_photo')->getClientOriginalExtension();
            $filename = now()->timestamp . '-' . Str::uuid() . '.' . strtolower($extension);

            $request->file('profile_photo')->storeAs('profile', $filename, 'public');
            $updateData['profile_photo_path'] = $filename;
        }

        $user->update($updateData);

        return redirect()->route('applicant.profile')->with('success', 'Profile updated successfully.');
    }

    public function update(Request $request)
    {
        return $this->updateProfile($request);
    }

    public function updatePassword(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'applicant') {
            abort(403, 'Unauthorized');
        }

        if ($request->input('step') === 'verify') {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ]);

            return redirect()->route('applicant.account.security')
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

        return redirect()->route('applicant.account.security')->with('success', 'Password changed successfully.');
    }
}
