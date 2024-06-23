<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class participantes extends AuthenticatableUser implements Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'participantes';

    protected $fillable = [
        'evento_id',
        'nombre',
        'ap_pat',
        'ap_mat',
        'apellidos',
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

    public function evento()
    {
        return $this->belongsTo(eventos::class, 'evento_id','id');
    }

    public function roles()
    {
        return $this->belongsToMany(roles::class, 'participantes_roles');
    }
    // Métodos para verificar roles de un participante
    public function hasRole($roleId)
    {
        return $this->roles()->where('id', $roleId)->exists();
    }

    public function hasAnyRoles($roleIds)
    {
        return $this->roles()->whereIn('id', $roleIds)->exists();
    }

}
// class participantes extends Model
// {
//     protected $table = 'participantes';
//     protected $fillable = [
//         'evento_id',
//         'nombre',
//         'ap_pat',
//         'ap_mat',
//         'apellidos',
//         'email',
//         'password',
//         'curp',
//     ];

//     public function evento()
//     {
//         return $this->belongsTo(eventos::class, 'evento_id','id');
//     }
// }
