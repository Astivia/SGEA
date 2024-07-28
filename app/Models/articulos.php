<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos extends Model
{
    protected $table = 'articulos';

    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'evento_id',
        'titulo',
        'resumen',
        'archivo',
        'area_id',
        'estado'
    ];

    protected $appends = ['autor_correspondencia'];

    public function getAutorCorrespondenciaAttribute()
    {
        return $this->autores->where('correspondencia', 1)->first();
    }
    
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
        return $this->hasMany(articulosAutores::class, 'articulo_id', 'id');
    }

    public function revisores()
    {
        return $this->hasMany(revisoresArticulos::class, 'articulo_id', 'id');
    }


    public function getTituloCortoAttribute()
    {
        $textoCortado = substr($this->titulo, 0, 50);
        return "{$textoCortado} ...";
    }
    
}
