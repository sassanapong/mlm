<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Report_bonus_copyright extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'report_bonus_copyright';


}
