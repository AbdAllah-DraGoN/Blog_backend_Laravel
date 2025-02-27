<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $limit = min($limit, 100);

        $users = User::paginate($limit);

        return response()->json([
            'data'=> $users->all(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'per_page' => $users->perPage(),
            'total' => $users->total(),
        ]);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|min:3|max:25',
            'email'=>'required|string|email|max:50|unique:users,email',
            'password'=>'required|string|min:5|confirmed',
            'image'=>['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('usersImages', 'public');
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            "image"=> 'storage/' . $path,
        ]);

        return response()->json([
            'message' => 'User Registered Successfully',
            // "data"=> $user // i comment user data because need to login after register
        ], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'invalid email or password'
            ], 401);
        }

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
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Successfully',
        ]);
    }


    public function destroy(Request $request)
    {
        // 1- get the user data by user token
        $user = $request->user(); // === $user = Auth::user();

        // 2- delete user image
        if ($user->image) {
            $path = str_replace('storage/', '', $user->image);
            Storage::disk('public')->delete($path);
        }

        // 3- Delete images from user posts
        $posts = $user->posts;
        foreach($posts as $post){
            $path = str_replace('storage/', '', $post['image']);
            Storage::disk('public')->delete($path);
        }

        // 4- delete the user
        $user->delete();


        return response()->json([
            'message' => 'User Has Been Deleted Successfully',
        ]);
    }
}
