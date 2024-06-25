<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comite_editorial extends Model
{
    protected $table = 'comite_editorial';
    protected $fillable = ['evento_id','participante_id'];
    
    //llaves foraneas
    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }

    public function participante()
    {
        return $this->belongsTo(participantes::class, 'participante_id','id');
    }
}
