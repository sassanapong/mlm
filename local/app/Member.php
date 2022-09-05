<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    // Name table
    protected $table = 'member';
    // guarded
    protected $guarded = [];
}
