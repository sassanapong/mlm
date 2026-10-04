<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterApplication extends Model
{
    protected $table = 'register_applications';

    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function formData()
    {
        return json_decode($this->form_data, true) ?: [];
    }

    public function itemList()
    {
        return json_decode($this->items, true) ?: [];
    }

    public function fileList()
    {
        return json_decode($this->files, true) ?: [];
    }
}
