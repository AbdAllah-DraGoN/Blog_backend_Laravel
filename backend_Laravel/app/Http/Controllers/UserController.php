<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            "data"=> $users,
            'count'=> count($users)
        ], 201);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|min:3|max:15',
            'email'=>'required|string|email|max:30|unique:users,email',
            'password'=>'required|string|min:5|confirmed',
        ]);

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User Registered Successfully',
            "data"=> $user
        ], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);

        if(! Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'invalid email or password',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully',
            "data"=> $user,
            "token"=> $token,
        ], 201);
    }


    public function show()
    {
        $user = Auth::user();

        return response()->json([
            "data"=> $user
        ], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Successfully',
        ]);
    }
}
