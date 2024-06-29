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
        Schema::create('comite_editorial', function (Blueprint $table) {
            $table->id(); 

            $table->unsignedInteger('evento_id')->nullable();
            $table->unsignedInteger('usuario_id')->nullable();

            $table->string('rol', 30);

            $table->foreign(['evento_id', 'usuario_id'])->references(['evento_id', 'usuario_id'])->on('participantes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comite_editorial');
    }
};
