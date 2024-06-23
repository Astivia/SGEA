<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function permisos()
    {
        return $this->belongsToMany(permisos::class, 'roles_permisos');
    }

    public function participantes()
    {
        return $this->belongsToMany(participantes::class, 'participantes_roles');
    }
    // Métodos para asignar/quitar roles a participantes
    public function attachParticipant($participantId)
    {
        $this->participantes()->attach($participantId);
    }

    public function detachParticipant($participantId)
    {
        $this->participantes()->detach($participantId);
    }
}
