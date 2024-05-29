<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    public function downlines()
    {
        return $this->hasMany(Customers::class, 'upline_id');
    }

    public function downlinesWithTypeAOrB()
    {
        return $this->downlines()->whereIn('type_upline', ['A', 'B']);
    }
}
