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
        Schema::create('autores_externos', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nombre',60);
            $table->string('ap_pat',30);
            $table->string('ap_mat',30);
            $table->integer('sexo');
            $table->string('afiliacion',300);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores_externos');
    }
};
