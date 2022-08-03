<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\follow;

class UserController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'first_name'=> 'required|string',
            'last_name'=> 'required|string',
            'username'=> 'required|string|unique:users,username',
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
            'fisrtname' => $user->first_name,
            'lastname' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'token' => $token,
        ];

        return Response($response, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'username'=> 'required|string',
            'password'=> 'required|string',
        ]);

        $user = User::where("username", $fields["username"])->first();

        if(!$user || !Hash::check($fields["password"], $user->password) ){
            return Response([
                "message"=>"Wrong criditials!"
            ], 401);
        }

        $token = $user->createToken('mytoken')->plainTextToken;

        $response = [
            'fisrtname' => $user->first_name,
            'lastname' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'token' => $token,
        ];

        return Response($response, 200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return Response([
            "message" => "logged out!"
        ]);
    }

    public function follows($id=1){
        $user = User::find($id);

        $followingsResult = $user->following()->get();
        $followingsResult = $followingsResult->makeHidden(['email_verified_at', 'email', 'remember_token', 'created_at', 'updated_at'])->toArray();

        $followersResult = $user->followers()->get();
        $followersResult = $followersResult->makeHidden(['email_verified_at', 'email', 'remember_token', 'created_at', 'updated_at'])->toArray();

        return Response([
            "following" => $followingsResult,
            "followers" => $followersResult,
        ]);
    }
}
