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
        'ap_pat',
        'ap_mat',
        'email',
        'password',
        'curp',
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->ap_pat} {$this->ap_mat} {$this->nombre}";
    }

    public function eventos()
    {
        return $this->belongsToMany(eventos::class, 'participantes', 'usuario_id', 'evento_id');
    }


}
