<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\AuthResource;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::all();

        if($users->isEmpty()){
            return response()->json(['No users Found'], 404);
        }

        return response()->json([
            'message'=> 'Users retrieved Successfully',
            'data'=>AuthResource::collection($users)
        ]);
    }

    public function login(Request $request)
    {
        $validated_user = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email', $validated_user['email'])->first();

        if (! $user || ! Hash::check($validated_user['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new AuthResource($user),
        ]);
    }

    public function register(Request $request)
{

    $validated_user = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8', 
    ]);

    $user = User::create([
        'name' => $validated_user['name'],
        'email' => $validated_user['email'],
        'password' => Hash::make($validated_user['password']),
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'User registered successfully',
        'token' => $token,
        'user' => new AuthResource($user),
    ], 201); 
}

}
