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

            $table->integer('evento_id')->unsigned()->index();
            $table->unsignedInteger('usuario_id')->index();
            
            $table->integer('articulo_id')->unsigned()->index();

            $table->integer('puntuacion')->nullable();
            $table->string('comentarios', 150)->nullable();

            $table->primary(['evento_id', 'usuario_id', 'articulo_id']);

            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign(['evento_id', 'usuario_id'])->references(['evento_id', 'usuario_id'])->on('participantes');
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
