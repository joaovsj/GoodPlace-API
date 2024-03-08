<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Place;

class PlaceController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'body'   => Place::orderByDesc('created_at')->get()
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
        if(Place::create($request->all())){
            
            return response()->json([
                'status' => true,
                'message' => 'Lugar cadastrado como Sucesso!'
            ]);
        }

        return response()->json([
            'status'=> false,
            'message' => 'Erro ao cadastrar lugar!'
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $place)
    {
        $placeData = Place::find($place);

        
        if(isset($placeData)){

            return response()->json([
                'status' => true,
                'body' => $placeData, 
            ], 200);
            
            
        }

        return response()->json([
            'status' => false,
            'message' => 'Lugar não encontrado!'
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
        $place = Place::find($id);

        if(isset($place)){

            $result = $place->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Lugar atualizado com sucesso!'
            ], 200);
            
            
        }

        return response()->json([
            'status' => false,
            'message' => 'Lugar não encontrado!'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Place::destroy($id)){
            
            return response()->json([
                'status' => true,
                'messsage' => 'Lugar deletado com sucesso!'
            ], 201);
        }

        return response()->json([
            'status' => false,
            'message' => 'Lugar não encontrado!'
        ], 404);
    }
}
