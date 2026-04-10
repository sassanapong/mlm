<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product_Images extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'products_images';

    protected $fillable = [
        'product_id_fk',
        'img_url',
        'product_img',
        'image_default'
    ];

    // guarded
    protected $guarded = [];
}
