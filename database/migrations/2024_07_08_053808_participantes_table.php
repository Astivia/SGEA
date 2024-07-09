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
        Schema::create('participantes', function (Blueprint $table) {
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('rol')->nullable();
            $table->timestamps();

            $table->primary(['evento_id','usuario_id']);
            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
