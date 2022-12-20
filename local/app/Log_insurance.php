<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Log_insurance extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'log_insurance';
     // guarded
     protected $guarded = [];


}
