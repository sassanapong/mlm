<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProductsUnit extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'dataset_product_unit';

    protected $fillable = [
        'product_unit_id',
        'product_unit',
        'lang_id',
        'status'
    ];

    // guarded
    protected $guarded = [];
}
