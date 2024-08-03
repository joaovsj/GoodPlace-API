<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Post;
use \App\Models\User;
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
                ->where('posts.user_id', $id) 
                ->join('places', 'posts.place_id', '=', 'places.id')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->join('images_posts', 'posts.id', '=', 'images_posts.post_id')
                ->select(
                    'posts.id',
                    'posts.assessment',
                    'posts.description',
                    'posts.details',
                    'posts.user_id',
                    'posts.place_id',
                    'posts.created_at',
                    'posts.updated_at',
                    'places.name as name',
                    'places.cep',
                    'places.address',
                    'places.number',
                    'places.neighborhood',
                    'places.city',
                    'places.state',
                    'places.country',
                    'places.category_id',
                    'images_posts.name as image',
                    'users.name as username',
                    'users.public_token'
                )->get();


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
                'message' => 'Postagem cadastrada com Sucesso!',
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
    public function show(string $id)
    {        

        $exists = Post::find($id);

        if(!$exists){
            return response()->json([
                'status' => false,
                'message' => 'Postagem não encontrada!'
            ], 404);    
        }        

        $post = DB::table('posts')
            ->join('places','posts.place_id','=', 'places.id')
            ->join('categories', 'places.category_id', '=', 'categories.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('images_posts', 'posts.id', '=', 'images_posts.post_id')
            ->select(
                'posts.*', 
                'places.*', 
                'images_posts.name as image', 
                'users.name as username', 
                'users.public_token',
                'categories.name as category'
            )
            ->where('posts.id', $id)
            ->get();

        
        // dd($id); // depois descobrir qual é a razão pela qual o id está trocando dentro da função

        foreach ($post as $key => $value) {
            $quantityRegisters = DB::table('comments')->where('post_id', '=', $id)->count();
            $post[$key]->comments = $quantityRegisters;
        }   

        return response()->json([
            'status' => true,
            'body' => $post, 
        ], 200); 
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
        if(!$id){
            return response()->json([
                'status' => false,
                'message' => 'Sem id!'
            ], 404);
        }


        $image = ImagePost::where('post_id', '=', $id)->get();

        if(sizeof($image) > 0){

            $path = public_path('img/places/') . $image[0]->name;  
            \file_exists($path) ? unlink($path) : null;  
        }

        $deleted = DB::table('comments')->where('post_id', '=', $id)->delete();
        $deleted = DB::table('images_posts')->where('post_id', '=', $id)->delete();
        
        if(Post::destroy($id)){
            
            return response()->json([
                'status' => true,
                'message' => 'Postagem deletada com sucesso!'
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

    // method responsible to return all posts by name sent by user
    public function getAllCommentsByName($name){


        if($name == "all"){

            $posts = DB::table('posts')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->join('places', 'posts.place_id', '=', 'places.id')
                ->join('images_posts', 'posts.id', '=', 'images_posts.post_id')
                ->select('posts.*', 'images_posts.name as image', 'users.name as username', 'places.name as place')
                ->get();

        }else{

            $posts = DB::table('posts')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->join('places', 'posts.place_id', '=', 'places.id')
                ->join('images_posts', 'posts.id', '=', 'images_posts.post_id')
                ->select('posts.*', 'images_posts.name as image', 'users.name as username', 'places.name as place')
                ->where('places.name','like', "%$name%")
                ->get();

        }

        if(count($posts) > 0){    

            foreach ($posts as $key => $value) {
                $quantityRegisters = DB::table('comments')->where('post_id', '=', $value->id)->count();
                $posts[$key]->comments = $quantityRegisters;
            }   
            
            return response()->json([
                'status' => true,
                'body' => $posts
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => "Local não encontrado!"
        ], 404);
    }

    // method responsible to get all users by field name sent by user
    public function getPeopleByName($name){
        
        if($name == "all"){

            $users = DB::table('users')
                ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
                ->leftJoin('comments', 'users.id', '=', 'comments.user_id')
                ->leftJoin('places', 'posts.place_id', '=', 'places.id')
                ->leftJoin('images_users', 'users.id', '=', 'images_users.user_id')
                ->select(
                    'users.*',
                    'images_users.name as image',
                    DB::raw('COUNT(DISTINCT posts.id) as posts_done'),
                    DB::raw('COUNT(DISTINCT comments.id) as comments_done'),
                    DB::raw('COUNT(DISTINCT places.id) as places_visited')
                )
                ->groupBy('users.id', 'images_users.name')
                ->orderByRaw('users.created_at DESC')
                ->get();                    
        
        } else{

            $users = DB::table('users')
                ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
                ->leftJoin('comments', 'users.id', '=', 'comments.user_id')
                ->leftJoin('places', 'posts.place_id', '=', 'places.id')
                ->leftJoin('images_users', 'users.id', '=', 'images_users.user_id')
                ->select(
                    'users.*',
                    'images_users.name as image',
                    DB::raw('COUNT(DISTINCT posts.id) as posts_done'),
                    DB::raw('COUNT(DISTINCT comments.id) as comments_done'),
                    DB::raw('COUNT(DISTINCT places.id) as places_visited')
                )
                ->where('users.name', 'like', "%$name%")
                ->groupBy('users.id', 'images_users.name')
                ->get();

        }



        
        if(count($users) > 0){    
            
            return response()->json([
                'status' => true,
                'body' => $users
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => "Nenhum usuário encontrado!"
        ], 404);
    }

}
