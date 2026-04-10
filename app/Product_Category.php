<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product_Category extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'dataset_categories';

    protected $fillable = [
        'category_id',
        'category_name',
        'lang_id',
        'status'
    ];

    // guarded
    protected $guarded = [];
}
