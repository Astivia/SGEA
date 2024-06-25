<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos_autores extends Model
{
    protected $table = 'articulos_autores';
    protected $fillable = ['articulo_id','autor_id'];
    
    //llaves foraneas
    public function articulo ()
    {
        return $this->belongsTo(articulos::class, 'articulo_id','id');
    }

    public function autor ()
    {
        return $this->belongsTo(autores::class, 'autor_id','id');
    }
}
