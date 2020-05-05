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
        return true;
        $lastTicket = $user->ticketsAsClient()->orderBy('created_at', 'desc')->first();

        if (!$lastTicket) return true;

        $now = Carbon::now();
        $twentyFourHoursPast = $now->diffInMinutes($lastTicket->created_at) > (60 * 24);

        if ($twentyFourHoursPast) return true;

        return false;
    }

    public function respond(User $user, Ticket $ticket)
    {
        if (!$ticket->is_closed) {
            if ($user->role === null) {
                $lastMessage = $ticket->messages()->orderBy('created_at', 'desc')->first();

                $isLastMessageFromManager = $lastMessage->is_from_manager;

                return $isLastMessageFromManager;
            } else if ($user->isManager() && $ticket->assignedTo($user->id)) {
                return true;
            }
        }

        return false;
    }
}
