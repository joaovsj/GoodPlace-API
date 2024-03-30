<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Post;

class PostController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'body'   => Post::orderByDesc('created_at')->get()
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
        if(Post::create($request->all())){
            
            return response()->json([
                'status' => true,
                'message' => 'Postagem cadastrada como Sucesso!'
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
}
