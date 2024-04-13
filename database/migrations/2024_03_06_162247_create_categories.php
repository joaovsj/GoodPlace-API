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
        DB::table('categories')->insert(['name' => 'Posto de Saúde']);   

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
