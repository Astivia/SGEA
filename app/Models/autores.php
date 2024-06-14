<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class autores extends Model
{
    protected $table = 'autores';
    protected $fillable = ['participante_id','afiliacion'];
    
    //llave foranea
    public function participante()
    {
        return $this->belongsTo(participantes::class, 'participante_id','id');
    }
}
