<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos extends Model
{
    protected $table = 'articulos';
    protected $fillable = ['evento_id','titulo','area_id'];
    
    //llave foranea
    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }

    public function area()
    {
        return $this->belongsTo(areas::class, 'area_id','id');
    }
}
