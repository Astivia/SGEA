<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventos extends Model
{
    protected $table = 'eventos';
    protected $fillable = [
        'nombre',
        'acronimo',
        'fecha_inicio',
        'fecha_fin',
        'edicion',
        'img'
    ];

}
