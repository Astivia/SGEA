<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos extends Model
{
    protected $table = 'articulos';

    protected $primaryKey = [
        'evento_id', 
        'id'
    ];

    public $incrementing = false;

    protected $fillable = [
        'id',
        'evento_id',
        'titulo',
        'resumen',
        'archivo',
        'area_id',
        'estado'
    ];
    
    //llave foranea
    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }

    public function area()
    {
        return $this->belongsTo(areas::class, 'area_id','id');
    }

    public function autores()
    {
        return $this->hasManyThrough(usuarios::class, articulosAutores::class, 'evento_id', 'id', 'articulo_id', 'usuario_id');
    }

    public function revisores()
    {
        return $this->hasManyThrough(usuarios::class, revisoresArticulos::class, 'evento_id', 'id', 'articulo_id', 'usuario_id');
    }

    // public function autores()
    // {
    //     return $this->belongsToMany(autores::class, 'articulos_autores', 'id_articulo', 'autor_id_autor')
    //                 ->withPivot('autor_id_ext');
    // }

    // public function autoresExternos()
    // {
    //     return $this->belongsToMany(autores_externos::class, 'articulos_autores', 'id_articulo', 'autor_id_ext')
    //                 ->withPivot('autor_id_autor');
    // }

    // public function revisores()
    // {
    //     return $this->belongsToMany(usuarios::class, 'revisores_articulos', 'articulo_id', 'usuario_id')
    //                ->withPivot('puntuacion', 'comentarios')
    //                ->as('revisor'); // Define alias for clarity
    // }


    
}
