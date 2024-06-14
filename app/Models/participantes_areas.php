<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participantes_areas extends Model
{
    protected $table = 'participantes_areas';
    protected $fillable = ['participante_id','area_id'];
    
    //llave foranea
    public function participante()
    {
        return $this->belongsTo(participantes::class, 'participante_id','id');
    }

    public function area ()
    {
        return $this->belongsTo(areas::class, 'area_id','id');
    }
}
