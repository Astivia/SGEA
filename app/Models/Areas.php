<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class areas extends Model
{
    protected $table = 'areas';
    protected $fillable = ['nombre','descripcion'];

    public function articulos()
    {
        return $this->hasMany(articulos::class);
    }
}
