<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'first_name'=> 'required|string',
            'last_name'=> 'required|string',
            'username'=> 'required|unique:users,username',
            'email'=> 'required|unique:users,email',
            'password'=> 'required|string|confirmed'
        ]);

        $user = User::create([
            'first_name'=> $fields['first_name'],
            'last_name'=> $fields['last_name'],
            'username'=> $fields['username'],
            'email'=> $fields['email'],
            'password'=> bcrypt($fields['password']),
        ]);

        $token = $user->createToken('mytoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return Response($response, 201);
    }
}
