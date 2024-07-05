<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventos extends Model
{
    protected $table = 'eventos';
    protected $fillable = [
        'logo',
        'nombre',
        'acronimo',
        'fecha_inicio',
        'fecha_fin',
        'edicion',
        'url'
    ];

    public function articulos()
    {
        return $this->hasMany(articulos::class);
    }

    public function revisores()
    {
        return $this->hasManyThrough(usuarios::class, articulos::class);
    }

    public function autores()
    {
        return $this->hasManyThrough(usuarios::class, articulos::class);
    }

}
