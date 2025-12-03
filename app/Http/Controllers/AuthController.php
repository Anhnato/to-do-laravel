<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        //Validate Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        //Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Always hash passwords!
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success','Welcome to SunnyDay Tasks!');
    }

    public function login(Request $request){
        //Validate Inputs
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //Attemp to login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // Security: Prevent session fixation
            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        // Login Failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
