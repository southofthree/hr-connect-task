<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'manager_id',
        'is_closed'
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }
}
