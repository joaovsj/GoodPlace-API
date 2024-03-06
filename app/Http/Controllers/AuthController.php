<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Http\Requests\StoreUserRequest;

use \App\Models\User;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request){

        // die(var_dump($request->all()));

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


    public function login(Request $request){

        $data = $request->all(); 


        die($data);
    }

    public function teste(){
        return response()->json([
            'status' => true
        ]);
    }

}
