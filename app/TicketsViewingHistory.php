<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketsViewingHistory extends Model
{
    protected $table = 'tickets_viewing_history';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'updated_at'
    ];
}
