<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StockMovement extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'db_stock_movement';
    // guarded
    protected $guarded = [];
}
