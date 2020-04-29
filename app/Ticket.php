<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'author',
        'manager',
        'is_closed'
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
