<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\participantes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $rol1 =Role::create(['name'=>'Administrador']);
        $rol2 =Role::create(['name'=>'Organizador']);
        $user =participantes::find(1);
        $user->assignRole($rol1) ;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
