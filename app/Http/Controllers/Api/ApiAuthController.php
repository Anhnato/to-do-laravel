<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    //API Login
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // Defien Scope
        $scopes = ['tasks:read', 'tasks:write', 'categories:manage'];

        //Create the Token with scopes
        $token = $user->createToken('ReadOnlyToken', ['tasks:read'])->plainTextToken;

        return response()->json([
            'message' => 'Token issued successfully',
            'token' => $token,
            'user' => $user
        ]);
    }

    //API Logout
    public function logout(Request $request)
    {
        // Delete only the token used for this specific request
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
