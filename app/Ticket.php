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

    public function close()
    {
        $this->is_closed = true;

        $this->save();
    }

    public function belongsToUser(int $userId): bool
    {
        return $userId === $this->client_id || $userId === $this->manager_id;
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }
}
