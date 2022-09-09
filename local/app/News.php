<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class news extends Model
{
    use Notifiable;
    // Name table
    protected $table = 'new';

    protected $fillable = [
        'title_news',
        'image_news',
        'upload_video',
        'link_video',
        'video_type',
        'detail_news',
        'date_news'
    ];

    protected $guard = 'member';
}
