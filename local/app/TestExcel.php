<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TestExcel extends Authenticatable
{
    use Notifiable;
    // Name table
    protected $table = 'test_excel';
    // guarded
    protected $guarded = [];
}
