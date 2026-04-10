<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'member';
    // guarded
    protected $guarded = [];

    protected $guard = 'member';
}
