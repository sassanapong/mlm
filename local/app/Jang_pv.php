<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Jang_pv extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'jang_pv';
     // guarded
     protected $guarded = [];


}
