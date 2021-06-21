<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate(
            [
                'name'=>'required',
                'email'=>'required|string|unique:users,email',
                'password'=>'required|string|confirmed',

            ]
            );
            $user = User::create(
                [
                    'name'=>$fields['name'],
                    'email'=>$fields['email'],
                    'password'=>bcrypt($fields['password'])
                ]
                );
                $token = $user->createToken('apptoken')->plainTextToken;
                $response =
                [
                    'user'=>$user,
                    'token'=>$token
                ];
                return response($response,201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate(
            [
                'email'=>'required|string',
                'password'=>'required|string',

            ]
            );
            //email check
            $user = User::where('email',$fields['email'])->first();
            //check passowrd
            if(!$user || !Hash::check($fields['password'],$user->password))
            {
                return response(
                    [
                        'message'=>'Bad Crentials'
                    ],401
                );
            }
                $token = $user->createToken('apptoken')->plainTextToken;
                $response =
                [
                    'user'=>$user,
                    'token'=>$token
                ];
                return response($response,201);
    }
    public function logout(Request $response)
    {
        auth()->user()->tokens()->delete();
        return[
            'message'=>'Logged out !'
        ];
    }
    //
}
