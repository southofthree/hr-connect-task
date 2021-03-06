<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeManagers($q)
    {
        return $q->whereRole('manager');
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function ticketsAsClient()
    {
        return $this->hasMany('App\Ticket', 'client_id');
    }
}
