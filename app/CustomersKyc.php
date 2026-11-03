<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersKyc extends Model
{
    protected $table = 'customers_kyc';
    protected $guarded = [];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
}
