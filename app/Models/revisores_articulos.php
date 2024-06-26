<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class revisores_articulos extends Model
{
    protected $table = 'revisores_articulos';

    protected $primaryKey = ['evento_id', 'usuario_id', 'articulo_id'];

    public $incrementing = false;

    protected $fillable = [
        'evento_id',
        'usuario_id',
        'articulo_id',
        'puntuacion',
        'comentarios',
    ];
    
    // Define relationships
    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'usuario_id');
    }

    public function articulo()
    {
        return $this->belongsTo(articulos::class, 'articulo_id','id');
    }
}
