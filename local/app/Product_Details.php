<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product_Details extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'products_details';

    protected $fillable = [
        'product_id_fk',
        'product_name',
        'title',
        'descriptions',
        'products_details',
        'lang_id'
    ];

    // guarded
    protected $guarded = [];
}
