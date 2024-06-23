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
        Schema::create('roles_permisos', function (Blueprint $table) {
            $table->primary(['roles_id', 'permisos_id']);
            $table->foreignId('roles_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permisos_id')->constrained('permisos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_permisos');
    }
};
