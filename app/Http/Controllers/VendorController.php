<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; // Add this line

use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|unique:vendors,email',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create a new user
        $vendor = Vendor::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // Generate an API token for the user
        $token = $vendor->createToken('myapptoken')->plainTextToken;

        $response = [
            'vendor' => $vendor,
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

        $vendor = Vendor::where('email', $request->email)->first();

        if (!$vendor || !Hash::check($request->password, $vendor->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'vendor' => $vendor,
            'token' => $vendor->createToken('mobile', ['role:vendor'])->plainTextToken
        ]);
    }
}
