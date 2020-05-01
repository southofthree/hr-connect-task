<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\TicketClosed;

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

        event(new TicketClosed($this));
    }

    public function assignedTo(int $userId)
    {
        return $this->manager_id === $userId;
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

    public function client()
    {
        return $this->belongsTo('App\User', 'client_id');
    }
}
