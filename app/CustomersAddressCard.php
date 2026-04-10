<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersAddressCard extends Model
{

    // Name table
    protected $table = 'customers_address_card';
    // guarded
    protected $guarded = [];
}
