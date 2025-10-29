<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }

    // Login user
    public function login(Request $request)
    {
        // Validate only email and password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $deviceName = $request->header('Device-Name', 'default');

        $user->tokens()->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }


    // Get authenticated user
    public function user(Request $request)
    {
        $user = $request->user();

        if (! $user && $user->expires_at != null) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $validToken = $this->validateToken($token->plainTextToken);

        return response()->json([
            'message' => 'Token is valid',
            'user' => $user
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }


    public function validateToken($token)
    {
        [$id, $tokenString] = explode('|', $token, 2);

        $accessToken = PersonalAccessToken::find($id);

        if (! $accessToken || ! hash_equals($accessToken->token, hash('sha256', $tokenString))) {
            return response()->json(['message' => 'Token invalid'], 401);
        }

        return response()->json([
            'message' => 'Token valid',
            'user' => $accessToken->tokenable
        ]);
    }

}
