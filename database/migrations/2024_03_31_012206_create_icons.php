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
        Schema::create('icons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->timestamps();
        });


        DB::table('icons')->insert(['name' => 'Facebook']);
        DB::table('icons')->insert(['name' => 'Instagram']);
        DB::table('icons')->insert(['name' => 'Whatsapp']);
        DB::table('icons')->insert(['name' => 'LinkedIn']);
        DB::table('icons')->insert(['name' => 'Telephone']);
        DB::table('icons')->insert(['name' => 'Twitter']);
        DB::table('icons')->insert(['name' => 'Github']);
        DB::table('icons')->insert(['name' => 'Email']);    

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icons');
    }
};
