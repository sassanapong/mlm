<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CUser extends Authenticatable
{
    use Notifiable;

    protected $guard = 'c_user';
    protected $table = 'customers';
    protected $guarded = [];

    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    // protected $hidden = [
    //     'password', 'remember_token',
    // ]
}
