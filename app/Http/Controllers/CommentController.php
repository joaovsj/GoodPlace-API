<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Comment;

class CommentController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'body'   => Comment::orderByDesc('created_at')->get()
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
        if(Comment::create($request->all())){
            
            return response()->json([
                'status' => true,
                'message' => 'Comentário cadastrado como Sucesso!'
            ]);
        }

        return response()->json([
            'status'=> false,
            'message' => 'Erro ao cadastrar comentário!'
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $comment)
    {
        $commentData = Comment::find($comment);

        
        if(isset($commentData)){

            return response()->json([
                'status' => true,
                'body' => $commentData, 
            ], 200);
            
            
        }

        return response()->json([
            'status' => false,
            'message' => 'Comentário não encontrado!'
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
        $comment = Comment::find($id);

        if(isset($comment)){

            $result = $comment->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Comentário atualizado com sucesso!'
            ], 200);
            
            
        }

        return response()->json([
            'status' => false,
            'message' => 'Comentário não encontrado!'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Comment::destroy($id)){
            
            return response()->json([
                'status' => true,
                'messsage' => 'comentário deletado com sucesso!'
            ], 201);
        }

        return response()->json([
            'status' => false,
            'message' => 'Comentário não encontrado!'
        ], 404);
    }
}
