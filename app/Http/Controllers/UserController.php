<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\follow;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'=> 'required|string',
            'last_name'=> 'required|string',
            'username'=> 'required|string|unique:users,username',
            'email'=> 'required|unique:users,email',
            'password'=> 'required|string|confirmed'
        ]);

        if($validator->fails()){
            return Response([
                'message' => $validator->messages(),
            ]);
        }else{
            $user = User::create([
                'first_name'=> $request->first_name,
                'last_name' => $request->last_name,
                'username'=> $request->username,
                'email'=> $request->email,
                'password'=> bcrypt($request->password),
            ]);
    
            $token = $user->createToken('mytoken')->plainTextToken;
    
            $response = [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'token' => $token,
            ];
    
            return Response($response, 201);
        }
    }

    public function getLogin(){
        return [
            'message' => 'You should login or register first'
        ];
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),
        [
            'username'=> 'required|string',
            'password'=> 'required|string',
        ]);

        if($validator->fails()){
            return Response([
                'message' => $validator->messages(),
            ]);
        }else{
            $user = User::where("username", $request->username)->first();
    
            if(!$user || !Hash::check($request->password, $user->password) ){
                return Response([
                    "message"=>"Wrong criditials!"
                ], 401);
            }
    
            return Response(new UserResource($user));
        }

    }

    public function logout(){
            Auth()->user()->tokens()->delete();
    
            return Response([
                "message" => "logged out!"
            ]);
        
    }

    public function follow(Request $request){
        $id = Auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if($validator->fails()){
            return Response([
                'message' => $validator->messages(),
            ]);
        }else{

            if(!empty(Auth()->user()->followings->where('id', $request->id)->first())){
                return Response([
                    'message' => 'you followed this user before.'
                ],400);

            }else{
                $followedUserId = $request->id;
                $followedUser = User::find($followedUserId);
    
                if($followedUser){
                    $follow = follow::create([
                        'user_id' => $id,
                        'followed_user_id' => $followedUserId,
                        'status' => "accept"
                    ]);
    
                    return response([
                        'message' => "follow request send successfully.",
                        'followed_user' => [
                                'id' => $followedUser->id,
                                'first_name' => $followedUser->first_name,
                                'last_name' => $followedUser->last_name,
                                'username' => $followedUser->username,
                            ]
                    ], 201);
                }else{
                    return Response([
                        'message' => 'There is no user with this id.'
                    ]);
                }

            }
        }

    }

    public function getFollows(){
        $id = Auth()->user()->id;
        $user = User::find($id);

        $followingsResult = $user->followings()->get();

        
        $followersResult = $user->followers()->get();

        return Response([
            "following" => FollowResource::collection($followingsResult),
            "followers" => FollowResource::collection($followersResult),
        ]);
    }

    public function update(Request $request){
        $id = Auth()->user()->id;
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'first_name'=> 'string',
            'last_name'=> 'string',
        ]);

        if($validator->fails()){
            return Response([
                'message' => $validator->messages(),
            ]);
        }else{
            if(isset($request->first_name)){
                $user->first_name = $request->first_name;
            }
            if(isset($request->last_name)){
                $user->last_name = $request->last_name;
            }
        }

        $user->save();

        $response = [
            "message" => "name changed successfully",
            'name' => $user->first_name . " " . $user->last_name,
        ];

        return Response($response, 200);

    }
}
