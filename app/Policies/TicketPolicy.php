<?php

namespace App\Policies;

use App\User;
use App\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;
use Carbon\Carbon;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        $lastTicket = $user->ticketsAsClient()->orderBy('created_at', 'desc')->first();

        $now = Carbon::now();

        if (!$lastTicket || $now->diffInMinutes($lastTicket->created_at) > (60 * 24)) {
            return true;
        }

        return false;
    }

    public function respond(User $user, Ticket $ticket)
    {
        if (!$ticket->is_closed) {
            if ($user->role === null) {
                $lastMessage = $ticket->messages()->orderBy('created_at', 'desc')->first();

                $isLastMessageFromManager = $lastMessage->is_from_manager;

                return $isLastMessageFromManager;
            } else if ($user->isManager()) {
                return true;
            }
        }

        return false;
    }
}
