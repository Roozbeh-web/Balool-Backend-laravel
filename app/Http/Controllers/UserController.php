<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowResource;
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
            'id' => $user->id,
            'fisrtname' => $user->first_name,
            'lastname' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'token' => $token,
        ];

        return Response($response, 201);
    }

    public function getLogin(){
        return [
            'message' => 'You should login or register first'
        ];
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
            Auth()->user()->tokens()->delete();
    
            return Response([
                "message" => "logged out!"
            ]);
        
    }

    public function follows(){
        $id = Auth()->user()->id;
        $user = User::find($id);

        $followingsResult = $user->followings()->get();

        
        $followersResult = $user->followers()->get();

        return Response([
            "following" => FollowResource::collection($followingsResult),
            "followers" => FollowResource::collection($followersResult),
        ]);
    }
}
