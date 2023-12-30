<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException; // Add this line

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|unique:admins,email',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create a new user
        $admin = Admin::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // Generate an API token for the user
        $token = $admin->createToken('myapptoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'admin' => $admin,
            'token' => $admin->createToken('mobile', ['role:admin'])->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        if (auth('admins')->check()) {
            $admin = auth('admins')->user();
            $admin->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out']);
        }

        return response()->json(['message' => 'Admin not authenticated'], 401);
    }
}
