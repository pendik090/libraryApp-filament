<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed, please try again',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::user();
            $token['token'] =  $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Login failed. Please check your credentials.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
