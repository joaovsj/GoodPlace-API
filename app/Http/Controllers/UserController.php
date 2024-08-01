<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use \App\Http\Requests\StoreUserRequest;
use \App\Models\User;
use \App\Models\ImageUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $fields = $request->validated();

        // dd($fields);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
            'phone' => $fields['phone'],
            'social_media' => $fields['social_media'],
            'public_token' => sha1($fields['name']) 
        ]);

        return response()->json([
            'status' => true,
            'body'   => $user,
            'token'  => $user->createToken('userLogged')->plainTextToken
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userData = User::find($id);
                
        if(isset($userData)){

            $imageName = $userData->image; 

            $userData['image']         = $imageName;
            $userData['placesVisited'] = $this->countAllRegistersInTable('posts', $id); 
            $userData['comments']      = $this->countAllRegistersInTable('comments', $id); 
            
            // $userData['image']         = $image;

            return response()->json([
                'status' => true,
                'body' => $userData,
            ], 200); 
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuário não encontrado!'
        ], 404);
    }


    private function getAbsolutePath($name){
        return public_path($name);
    }

    /**
     * Return the count of registers on Table based in ID 
     */ 
    private function countAllRegistersInTable($name, $id){
        $count = DB::table($name)->where('user_id', $id)->count();
        return $count;
    }


    public function getImage(string $name){

        $image = ImageUser::where('name', '=', $name)->get();

        // dd($image);
        
        if($image->count() > 0){
            
            // $nameImage = $userData->image->name;    

            $name = $image[0]->name;
            $path = public_path("img/profile/$name");

            if(file_exists($path)){
                
                return response()->file($path);
            }

            return response()->json("It doesn't exists");

        }

        return response()->json([
            'status' => false,
            'message' => 'Imagem não encontrada!'
        ], 404);
        
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $userData = User::find($id);

        if(isset($userData)){

            $result = $userData->update($request->all());

            return response()->json([
                'status' => true,
                'message' => "Usuário atualizado com sucesso!"
            ], 200);
            
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuário não encontrado!'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function icons(){
        return DB::table('icons')->get();
    }

    /**
     * Store the profile image of user
     */
    public function upload(Request $request){


        $id = $request->user_id; // id of the user
        $images = ImageUser::where('user_id', '=', $id)->get();

        if(sizeof($images) > 0){
            $path = public_path('img/profile/') . $images[0]->name;  
            \file_exists($path) ? unlink($path) : null;  
                
            $user = ImageUser::find($images[0]->id); // id of the database

        } else{
            $user = new ImageUser();
            $user->user_id = $id;
        }

        if($request->hasFile('image') and $request->file('image')->isValid()){

            $requestImage = $request->image;
            $extension = $requestImage->extension();

            $extensions = ['jpg', 'png', 'jpeg'];

            if(!in_array($extension, $extensions)){
                return response()->json([
                    'status' => false,
                    'message' => 'Formato da imagem é inválido!'
                ], 422);
            }

            // making a new name...
            $imageName = md5($requestImage->getClientOriginalName().strtotime("now")).".".$extension;
            // moving to folder
            $requestImage->move(public_path('img/profile'), $imageName);
            
            // setting to request index the new name.            
            $user->name = $imageName;            
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Imagem atualizada com sucesso!'
            ], 201);
        }   


        return response()->json([
            'status' => false,
            'message' => 'Imagem é inválida!'
        ], 422);
   
    }

    public function getUserByToken(string $token)
    {
        $userData = User::where('public_token', $token)->get();
        $userData = User::find($userData[0]->id); // avoid the problem userData be not an instance of users
                
        if(isset($userData)){

            $imageName = $userData->image; 

            $userData['image']         = $imageName;
            $userData['placesVisited'] = $this->countAllRegistersInTable('posts', $userData->id); 
            $userData['comments']      = $this->countAllRegistersInTable('comments', $userData->id); 

            return response()->json([
                'status' => true,
                'body' => $userData,
            ], 200); 
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuário não encontrado!'
        ], 404);
    }
}
