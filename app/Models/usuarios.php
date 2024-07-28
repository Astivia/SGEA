<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;


class usuarios extends AuthenticatableUser implements Authenticatable
{
    use HasFactory,Notifiable;
    use HasRoles;

    protected $table = 'usuarios';

    protected $fillable = [
        'foto',
        'nombre',
        'ap_paterno',
        'ap_materno',
        'email',
        'password',
        'curp',
        'telefono',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->nombre;
    }
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
        $this->save();
    }
    public function setEstado(string $estado){
        $this->estado = $estado;
        $this->save();
    }
    public function getEstado()
    {
        return $this->estado;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->ap_paterno} {$this->ap_materno} {$this->nombre}";
    }

    public function getNombreAutorAttribute()
    {
        return "{$this->nombre[0]}.{$this->ap_paterno} ";
    }

    public function articulos()
    {
        return $this->hasManyThrough(articulos::class, articulosAutores::class, 'usuario_id', 'evento_id', 'articulo_id');
        
    }

    public function revisiones()
    {
        return $this->hasManyThrough(articulos::class, revisoresArticulos::class, 'usuario_id', 'evento_id', 'articulo_id');
    }

    public function eventos(){
        return $this->belongsToMany(eventos::class, 'participantes', 'usuario_id', 'evento_id');
    }


}
