<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $login = $validated['login'];
        $remember = (bool) ($validated['remember'] ?? false);

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $login, 'password' => $validated['password']], $remember)) {
            $request->session()->regenerate();

            $user = $request->user();
            if ($user && $user->role === 'kasogartap') {
                $user->update(['role' => 'kaskogartap']);
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['login' => 'Username atau password salah.'])
            ->onlyInput('login');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
