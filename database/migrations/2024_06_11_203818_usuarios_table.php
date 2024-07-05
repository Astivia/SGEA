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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('curp', 18)->unique(); 
            $table->string('foto')->nullable();
            $table->string('nombre', 50);
            $table->string('ap_paterno', 100);
            $table->string('ap_materno', 100);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('telefono',10);
            $table->string('estado',20);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
