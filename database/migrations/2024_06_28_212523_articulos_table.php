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
        Schema::create('articulos', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('evento_id')->index();

            $table->string('titulo', 200)->nullable(false);
            $table->text('resumen')->nullable();
            $table->string('archivo',100)->nullable();
            $table->unsignedBigInteger('area_id')->index();
            $table->string('estado',15)->nullable(false);
            $table->timestamps();

            $table->unique(['evento_id','id'])->nullable(false);

            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
