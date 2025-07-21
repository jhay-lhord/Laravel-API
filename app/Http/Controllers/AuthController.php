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
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new AuthResource($user),
        ]);
    }
}
