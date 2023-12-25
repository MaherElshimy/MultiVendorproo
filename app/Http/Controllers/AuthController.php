<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'grandfather_name' => 'required|string|max:255',
            'governorate' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'property_number' => 'required|string|max:255',
            'apartment_number' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users',
        ]);

        // Create a new user
        $user = User::create([
            'first_name' => $fields['first_name'],
            'father_name' => $fields['father_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'grandfather_name' => $fields['grandfather_name'],
            'governorate' => $fields['governorate'],
            'city' => $fields['city'],
            'street_name' => $fields['street_name'],
            'property_number' => $fields['property_number'],
            'apartment_number' => $fields['apartment_number'],
            'phone_number' => $fields['phone_number'],
        ]);

        // Generate an API token for the user
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Log the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Wrong password.'], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 201);
    }
}
