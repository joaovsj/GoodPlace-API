<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('assessment', 5); // availiação(numero vai representar a quantidade de estrelas)
            $table->text("description");
            $table->json("details");          // cada indice vai representar um icone(categoria(conforto/atendimento)) e o valor a qualidade delas
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("place_id");
            
            $table->foreign("user_id")
                ->references('id')
                ->on('users');

            $table->foreign("place_id")
                ->references('id')
                ->on('places');

            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
