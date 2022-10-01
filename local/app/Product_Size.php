<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product_Size extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'dataset_size';

    protected $fillable = [
        'size',
        'status'
    ];

    // guarded
    protected $guarded = [];
}
