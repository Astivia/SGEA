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
}
