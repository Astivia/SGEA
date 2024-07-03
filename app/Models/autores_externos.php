<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class autores_externos extends Model
{
    protected $table = 'autores_externos';
    protected $fillable = ['foto','nombre','ap_pat','ap_mat','afiliacion','sexo'];
    
    //llave foranea
    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'usuario_id','id');
    }

    public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulos_autores', 'autor_id_ext', 'id_articulo')
                    ->withPivot('autor_id_autor');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->ap_pat} {$this->ap_mat} {$this->nombre}";
    }
}
