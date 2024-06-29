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
        Schema::create('participantes_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('evento_id')->unsigned()->nullable();
            $table->unsignedInteger('usuario_id')->nullable();

            $table->foreign(['evento_id', 'usuario_id'])->references(['evento_id', 'usuario_id'])->on('participantes');

            $table->integer('area_id')->unsigned()->nullable();
            $table->foreign('area_id')->references('id')->on('areas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes_areas');
    }
};
