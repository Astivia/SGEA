<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permisos extends Model
{
    protected $table = 'permisos';
    protected $fillable = [
        'nombre',
        'descripcion'
    ];
    public function roles()
    {
        return $this->belongsToMany(roles::class, 'roles_permisos');
    }

    // Métodos para asignar/quitar permisos a roles
    public function assignToRole($roleId)
    {
        $this->roles()->attach($roleId);
    }

    public function removeFromRole($roleId)
    {
        $this->roles()->detach($roleId);
    }
}
