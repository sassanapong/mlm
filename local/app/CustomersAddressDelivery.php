<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersAddressDelivery extends Model
{
    // Name table
    protected $table = 'customers_address_delivery';
    // guarded
    protected $guarded = [];
}
