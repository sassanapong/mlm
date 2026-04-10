<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class eWallet_tranfer extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'ewallet_tranfer';
    // guarded
    protected $guarded = [];
}
