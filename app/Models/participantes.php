<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


use Spatie\Permission\Traits\HasRoles;



class participantes extends AuthenticatableUser implements Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

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
