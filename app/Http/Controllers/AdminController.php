<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // 🔥 1. CONFIG ADMIN LOGIN (backup credentials)
        if (
            $email === config('admin.email') &&
            $password === config('admin.password')
        ) {
            $user = User::where('email', $email)->first();

            // kung wala sa DB, create admin user
            if (!$user) {
                $user = User::create([
                    'first_name' => 'Admin',
                    'last_name'  => 'User',
                    'email'      => config('admin.email'),
                    'password'   => Hash::make(config('admin.password')),
                    'role'       => 'admin',
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // 🔥 2. NORMAL DATABASE LOGIN
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();

            // check admin role
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized access.']);
            }

            if ((bool) ($user->is_active ?? true) === false) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is currently deactivated.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
