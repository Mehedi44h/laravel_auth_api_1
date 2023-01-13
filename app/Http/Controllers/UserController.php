<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'tc' => 'required',
        ]);

        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already taken',
                'stasut' => 'failed',
            ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tc' => json_decode($request->tc)
        ], 201);

        $token = $user->createToken($request->email)->plainTextToken;

        return response([
            'token' => $token,
            'message' => 'Registration Successfull',
            'status' => 'success',

        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;

            return response(
                [
                    'token' => $token,
                    'message' => 'Login Successfull',
                    'status' => 'success',

                ],
                200
            );
        }
        return response([
            'message' => 'Wrong credentials',
            'stasut' => 'failed',
        ], 400);
    }

    public function logout()
    {
        // auth()->user()->tokens()->delete();
        return response([
            'message' => 'logout success',
            'stasut' => 'logout',
        ], 200);
    }
    public function loged_user()
    {
        $loged_user = auth()->user();
        return response([
            'message' => 'loged user data success',
            'stasut' => 'success',
        ], 200);
    }

    public function chenge_password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $loged_user = auth()->user();
        $loged_user->password = Hash::make($request->password);
        $loged_user->save();
        return response([
            'message' => 'password chenge success',
            'stasut' => 'success',
        ], 200);
    }
}