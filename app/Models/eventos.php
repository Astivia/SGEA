<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventos extends Model
{
    protected $table = 'eventos';
    protected $fillable = [
        'logo',
        'nombre',
        'acronimo',
        'fecha_inicio',
        'fecha_fin',
        'edicion',
    ];

    public function participantes()
    {
        return $this->belongsToMany(usuarios::class, 'participantes', 'evento_id','usuario_id' );
    }

}
