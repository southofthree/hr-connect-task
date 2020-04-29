<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'text',
        'is_from_manager'
    ];

    public function attachments()
    {
        return $this->hasMany('App\MessageAttachment');
    }
}
