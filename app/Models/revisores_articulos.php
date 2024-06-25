<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class revisores_articulos extends Model
{
    protected $table = 'revisores_articulos';
    protected $fillable = ['participante_id','articulo_id'];
    
    //llave foranea
    public function participante()
    {
        return $this->belongsTo(participantes::class, 'participante_id','id');
    }

    public function articulo()
    {
        return $this->belongsTo(articulos::class, 'articulo_id','id');
    }
}
