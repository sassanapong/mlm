<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    // Name table
    protected $table = 'customers';
    // guarded
    protected $guarded = [];
}
