<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participantes extends Model
{
    protected $table = 'participantes';
    protected $fillable = [
        'usuario_id',
        'evento_id',
        'rol'
    ];

    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }
    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'usuario_id','id');
    }


}
