<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulosAutores extends Model
{
    protected $table = 'articulos_autores';

    protected $primaryKey = [
        'evento_id', 
        'usuario_id', 
        'articulo_id'
    ];
        
    public $incrementing = false;

    protected $fillable = [
        'evento_id',
        'articulo_id',
        'usuario_id',
        'orden',
        'correspondencia',
        'institucion',
        'email'
    ];

    

    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }

    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'usuario_id','id');
    }

    public function articulo()
    {
        return $this->belongsTo(articulos::class, 'articulo_id','id');
    }

}
