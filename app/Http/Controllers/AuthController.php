<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use \App\Http\Requests\LoginRequest;
use \App\Models\User;

class AuthController extends Controller
{
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
