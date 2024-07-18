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
        return $this->hasMany(articulosAutores::class, 'articulo_id', 'id');
    }

    public function revisores()
    {
        return $this->hasManyThrough(usuarios::class, revisoresArticulos::class, 'evento_id', 'id', 'articulo_id', 'usuario_id');
    }
    
}
