<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class participantes extends Model
{
    protected $table = 'participantes';
    protected $fillable = [
        'evento_id',
        'nombre',
        'apellidos',
        'email',
        'password',
        'curp',
    ];

    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }
}
