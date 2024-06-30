<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class autores extends Model
{
    protected $table = 'autores';
    protected $fillable = ['usuario_id','afiliacion'];
    
    //llave foranea
    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'usuario_id','id');
    }

    public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulos_autores', 'autor_id_autor', 'id_articulo')
                    ->withPivot('autor_id_ext');
    }
}
