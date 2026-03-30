<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        $guard = Auth::guard('web');
        if (!$guard->attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_active' => true], $remember)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $request->session()->regenerate();

        $user = $guard->user();
        if (!$user) {
            return back()->withErrors(['email' => 'Authentication failed.'])->withInput();
        }
        $allowedRoles = ['SuperAdmin', 'Admin', 'HR', 'RH', 'Manager'];
        $hasRole = $user->roles()->whereIn('name', $allowedRoles)->exists();

        if (!$hasRole) {
            $guard->logout();
            return back()->withErrors(['email' => 'Access denied.'])->withInput();
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function loginWithToken(Request $request)
    {
        $token = $request->query('token') ?? $request->input('token');
        if (!$token) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Token missing.']);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Invalid token.']);
        }

        if (!$user || !$user->is_active) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Access denied.']);
        }

        $allowedRoles = ['SuperAdmin', 'Admin', 'HR', 'RH', 'Manager'];
        $hasRole = $user->roles()->whereIn('name', $allowedRoles)->exists();
        if (!$hasRole) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Access denied.']);
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.dashboard');
    }
}
