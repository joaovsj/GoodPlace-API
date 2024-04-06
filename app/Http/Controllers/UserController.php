<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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

            $userData['placesVisited'] = $this->countAllRegistersInTable('posts', $id); 
            $userData['comments']      = $this->countAllRegistersInTable('comments', $id); 

            // dd($userData);
            // $userData['created_at'] = $userData

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

    /**
     * Return the count of registers on Table based in ID 
     */ 
    private function countAllRegistersInTable($name, $id){
        $count = DB::table($name)->where('user_id', $id)->count();
        return $count;
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

        $imageUser = new ImageUser(); 
        $imageUser->user_id = $request->user_id;

        if($request->hasFile('image') and $request->file('image')->isValid()){

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            // making a new name...
            $imageName = md5($requestImage->getClientOriginalName().strtotime("now")).".".$extension;
            // moving to folder
            $requestImage->move(public_path('img/profile'), $imageName);
            
            // setting to request index the new name.            
            $imageUser->name = $imageName;            
            $imageUser->save();

            return response()->json([
                'status' => true,
                'message' => 'Imagem atualizada com sucesso!'
            ], 201);
        }   


        return response()->json([
            'status' => false,
            'message' => 'Formato da imagem é inválido!'
        ], 422);
   
    }
}
