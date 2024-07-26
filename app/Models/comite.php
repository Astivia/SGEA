<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comite extends Model
{
    protected $table = 'comite_editorial';

    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'usuario_id',
        'evento_id',
        'rol'
    ];
}
