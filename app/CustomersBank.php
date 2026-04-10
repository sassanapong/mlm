<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersBank extends Model
{
    // Name table
    protected $table = 'customers_bank';
    // guarded
    protected $guarded = [];
}
