<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mdk_lrn extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'mdk_learning';

    protected $fillable = [
        'title_lrn',
        'detail_lrn',
        'image_lrn',
        'upload_video_lrn',
        'link_video_lrn',
        'video_type_lrn',
        'detail_lrn_all',
        'start_date_lrn',
        'end_date_lrn',
        'uploadfile_lrn'
    ];

    protected $guard = 'member';
}
