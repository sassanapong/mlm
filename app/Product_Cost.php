<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product_Cost extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'products_cost';

    protected $fillable = [
        'product_id_fk',
        'business_location_id',
        'currency_id',
        'cost_price',
        'selling_price',
        'member_price',
        'pv',
        'status',
        'status_shipping'
    ];

    // guarded
    protected $guarded = [];
}
