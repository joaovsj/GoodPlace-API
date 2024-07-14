<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('categories')->insert(['name' => 'Hotel']);
        DB::table('categories')->insert(['name' => 'Restaurante']);
        DB::table('categories')->insert(['name' => 'Loja']);
        DB::table('categories')->insert(['name' => 'Shopping']);
        DB::table('categories')->insert(['name' => 'Salão de Beleza']);
        DB::table('categories')->insert(['name' => 'Farmácia']);
        DB::table('categories')->insert(['name' => 'Padaria']);
        DB::table('categories')->insert(['name' => 'Hospital']);   
        DB::table('categories')->insert(['name' => 'Pousada']); 
        DB::table('categories')->insert(['name' => 'Quiosque']);
        DB::table('categories')->insert(['name' => 'Ponto Turístico']); 
        DB::table('categories')->insert(['name' => 'Praia']); 
        DB::table('categories')->insert(['name' => 'Centro']);    
        DB::table('categories')->insert(['name' => 'Praça']); 
        DB::table('categories')->insert(['name' => 'Museu']); 
        DB::table('categories')->insert(['name' => 'Parque']); 
        DB::table('categories')->insert(['name' => 'Motel']); 
        DB::table('categories')->insert(['name' => 'Circo']); 
        DB::table('categories')->insert(['name' => 'Aeroporto']); 
        DB::table('categories')->insert(['name' => 'Quitanda']); 
        DB::table('categories')->insert(['name' => 'Universidade']); 
        DB::table('categories')->insert(['name' => 'Faculdade']);
        DB::table('categories')->insert(['name' => 'Escola']);
        DB::table('categories')->insert(['name' => 'Empresa']); 
        DB::table('categories')->insert(['name' => 'Evento']);
        DB::table('categories')->insert(['name' => 'Sorveteria']); 
        DB::table('categories')->insert(['name' => 'Bar']); 
        DB::table('categories')->insert(['name' => 'Lanchonete']);
        DB::table('categories')->insert(['name' => 'Açougue']);
        DB::table('categories')->insert(['name' => 'Peixaria']);
        DB::table('categories')->insert(['name' => 'Papelaria']);
        DB::table('categories')->insert(['name' => 'Pet shop']);
        DB::table('categories')->insert(['name' => 'Academia']);
        DB::table('categories')->insert(['name' => 'Barbearia']);
        DB::table('categories')->insert(['name' => 'Clínica']);
        DB::table('categories')->insert(['name' => 'Estúdio']); 
        DB::table('categories')->insert(['name' => 'Agência']); 
        DB::table('categories')->insert(['name' => 'Joalheria']); 
        DB::table('categories')->insert(['name' => 'Oficina']); 
        DB::table('categories')->insert(['name' => 'Biblioteca']); 
        DB::table('categories')->insert(['name' => 'Teatro']); 
        DB::table('categories')->insert(['name' => 'Zoológico']); 
        DB::table('categories')->insert(['name' => 'Campo']); 
        DB::table('categories')->insert(['name' => 'Clube']); 
        DB::table('categories')->insert(['name' => 'Cassino']); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
