<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Order_products_list extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'db_order_products_list';


}
