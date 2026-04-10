<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mdk_ct extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'mdk_ct';

    protected $fillable = [
        'title_ct',
        'detail_ct',
        'image_ct',
        'upload_video_ct',
        'link_video_ct',
        'video_type_ct',
        'detail_ct_all',
        'start_date_ct',
        'end_date_ct',
        'uploadfile_ct'
    ];

    protected $guard = 'member';
}
