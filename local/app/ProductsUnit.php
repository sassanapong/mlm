<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProductsUnit extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'dataset_product_unit';
    // guarded
    protected $guarded = [];
}
