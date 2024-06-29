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
        // $table->unsignedBigInteger('evento_id')->nullable();
        // $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('SET NULL');
        Schema::create('participantes', function (Blueprint $table) {
            $table->unsignedInteger('evento_id');
            $table->unsignedInteger('usuario_id');
            $table->primary(['evento_id', 'usuario_id']);
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreign('evento_id')->references('id') ->on('eventos')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
