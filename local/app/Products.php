<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Products extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'products';

    protected $fillable = [
        'product_code',
        'category_id',
        'unit_id',
        'size_id',
        'orders_type_id',
        'product_voucher',
        'order_buy_member',
        'order_by_staff',
        'orderby',
        'status'
    ];

    // guarded
    protected $guarded = [];
}
