<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Orders extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'db_orders';


}
