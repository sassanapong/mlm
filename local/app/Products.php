<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Products extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'products';
    // guarded
    protected $guarded = [];
}
