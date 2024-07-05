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
        Schema::create('revisores_articulos', function (Blueprint $table) {
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('articulo_id');
            $table->unsignedBigInteger('usuario_id');
            $table->integer('puntuacion')->nullable();
            $table->string('similitud', 20)->nullable();
            $table->string('comentarios', 150)->nullable();
            $table->timestamps();

            $table->primary(['evento_id', 'articulo_id', 'usuario_id']);
            $table->foreign(['evento_id', 'articulo_id'])->references(['evento_id', 'id'])->on('articulos');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisores_articulos');
    }
};
