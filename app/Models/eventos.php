<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'estado'
    ];

    public function participantes(){
        return $this->belongsToMany(usuarios::class, 'participantes', 'evento_id','usuario_id' );
    }

    public function articulos()
    {
        return $this->hasMany(articulos::class);
    }

    public function revisores()
    {
        return $this->hasManyThrough(usuarios::class, articulos::class);
    }

    public function autores()
    {
        return $this->hasManyThrough(usuarios::class, articulos::class);
    }

    // Mutators
    public function getFechaInicioNormalAttribute($value)
    {
        return Carbon::parse($value)->locale('es')->isoFormat('dddd DD [de] MMMM [de] YYYY');
    }

    public function getFechaFinNormalAttribute($value)
    {
        return Carbon::parse($value)->locale('es')->isoFormat('dddd DD [de] MMMM [de] YYYY');
    }

    public function getEstadoAttribute($value)
    {
        $estados = [
            1 => 'Programado',
            2 => 'En Curso',
            3 => 'Finalizado',
            4 => 'Cancelado'
        ];

        return $estados[$value] ?? 'Desconocido';
    }

}
