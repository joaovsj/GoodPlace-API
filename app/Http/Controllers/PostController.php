<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Post;
use \App\Models\ImagePost;

use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $id = request('user_id');

        if($id){
            $posts = DB::table('posts')
                ->where('user_id', $id)
                ->join('places','posts.place_id','=', 'places.id')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->join('images_posts', 'posts.id', '=', 'images_posts.post_id')
                ->select('posts.*', 'places.*', 'images_posts.name as image', 'users.name as username', )
                ->get();


            foreach ($posts as $key => $value) {
                $quantityRegisters = DB::table('comments')->where('post_id', '=', $value->id)->count();
                $posts[$key]->comments = $quantityRegisters;
            }   
            
        }else{
            $posts = Post::orderByDesc('created_at')->get();
        }

        return response()->json([
            'status' => true,
            'body'   => $posts
        ]);
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
    public function store(Request $request)
    {   

        $post = Post::create($request->all());

        if($post){
            
            return response()->json([
                'status' => true,
                'message' => 'Postagem cadastrada como Sucesso!',
                'id'       => $post->id
            ]);
        }

        return response()->json([
            'status'=> false,
            'message' => 'Erro ao cadastrar postagem!'
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $post)
    {
        $postData = Post::find($post);

        
        if(isset($postData)){

            return response()->json([
                'status' => true,
                'body' => $postData, 
            ], 200); 
        }

        return response()->json([
            'status' => false,
            'message' => 'Postagem não encontrada!'
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
        $post = Post::find($id);

        if(isset($post)){

            $result = $post->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Postagem atualizada com sucesso!'
            ], 200);
            
            
        }

        return response()->json([
            'status' => false,
            'message' => 'Postagem não encontrada!'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Post::destroy($id)){
            
            return response()->json([
                'status' => true,
                'messsage' => 'Postagem deletada com sucesso!'
            ], 201);
        }

        return response()->json([
            'status' => false,
            'message' => 'Postagem não encontrada!'
        ], 404);
    }

    /**
     * Store the profile image of user
     */
    public function upload(Request $request){

        if(count($request->all()) == 0){
            return response()->json([
                'status' => false,
                'message' => 'Campo imagem vazio!'
            ], 422);
        }

        $imagePost = new ImagePost();
        $imagePost->post_id = $request->post_id;

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
            $requestImage->move(public_path('img/places'), $imageName);
            
            // setting to request index the new name.            
            $imagePost->name = $imageName;            
            $imagePost->save();

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

    /**
     * Method resposible to return post's image
     * 
     */
    public function getImage(string $name){

        $image = ImagePost::where('name', '=', $name)->get();

        // dd($image);
        
        if($image->count() > 0){
            
            // $nameImage = $userData->image->name;    

            $name = $image[0]->name;
            $path = public_path("img/places/$name");

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
}
