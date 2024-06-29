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
        Schema::create('articulos_autores', function (Blueprint $table) {
            $table->id();
            $table->integer('id_articulo')->unsigned()->index();
            $table->foreign('id_articulo')->references('id')->on('articulos');

            $table->integer('autor_id_autor')->nullable()->index(); 
            $table->foreign('autor_id_autor')->references('id')->on('autores');

            $table->integer('autor_id_ext')->nullable()->index();
            $table->foreign('autor_id_ext')->references('id')->on('autores_externos');

            $table->unique(['autor_id_autor', 'autor_id_ext'], 'autor_exclusivo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_autores');
    }
};
