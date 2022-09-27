<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Branch extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'branchs';
    // guarded
    protected $guarded = [];
}
