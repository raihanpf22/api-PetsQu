<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Admin extends Authenticable implements JWTSubject
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'admin_name',
        'email',
        'telp',
        'password'
    ];
    public function getJWTIdentifier()
    {
        # code...
        return $this->getKey();
    }

    public function GetJWTCustomClaims()
    {
        # code...
        return[];
    }

}
