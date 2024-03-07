<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use \App\Http\Requests\StoreUserRequest;
use \App\Http\Requests\LoginRequest;

use \App\Models\User;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request){

        $fields = $request->validated();

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
            'phone' => $fields['phone'],
            'social_media' => $fields['social_media'],
        ]);

        return response()->json([
            'status' => true,
            'body'   => $user,
            'token'  => $user->createToken('userLogged')->plainTextToken
        ]);
    }   


    public function login(LoginRequest $request){

        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if($user){
            if(Hash::check($credentials['password'], $user->password)){
                
                return response()->json([
                    'status' => true,
                    'body'   => $user,
                    'token'  => $user->createToken('userLogged')->plainTextToken
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message'  => "Usuário ou senha inválidos!"
        ]);
    }

    
}
