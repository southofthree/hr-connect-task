<?php

namespace App\Services;

use DB;
use Exception;
use LogicalException;
use App\User;
use App\Ticket;
use App\TicketsViewingHistory;
use Illuminate\Database\Eloquent\Collection;
use App\Events\TicketCreated;

class TicketService
{
    public static function createTicket(User $client, array $data): Ticket
    {
        try {
            DB::beginTransaction();

            $ticket = $client->ticketsAsClient()->create($data);

            $message = $ticket->messages()->create(['text' => $data['message']]);

            if (isset($data['attachment'])) {
                $message->attachFile($data['attachment']);
            }

            DB::commit();

            event(new TicketCreated($ticket, $message));

            return $ticket;
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public static function storeResponse(Ticket $ticket, User $user, array $data)
    {
        try {
            DB::beginTransaction();

            $isFromManager = $user->isManager();

            if ($isFromManager && !$ticket->assignedTo($user->id)) {
                throw new LogicalException('User is not assigned to the ticket');
            }

            $message = $ticket->messages()->create([
                'text' => $data['message'],
                'is_from_manager' => $isFromManager
            ]);
            
            if (isset($data['attachment'])) {
                $message->attachFile($data['attachment']);
            }

            $ticket->touch();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public static function getClientTickets(User $user): Collection
    {
        return $user->ticketsAsClient()
                    ->selectRaw("
                        id,
                        subject,
                        is_closed,
                        (select text from messages where ticket_id = tickets.id order by created_at asc limit 1) message
                    ")
                    ->orderBy('updated_at', 'desc')
                    ->get();
    }

    public static function getTickets(array $filters, User $user): Collection
    {
        $tickets = Ticket::selectRaw("
                    id,
                    subject,
                    is_closed,
                    (select text from messages where ticket_id = tickets.id order by created_at asc limit 1) message
                ")
                ->orderBy('updated_at', 'desc');

        foreach ($filters as $key => $value) {
            $query = self::getFilterQuery($key, $value, $user);

            if (!$query) continue;

            $tickets->whereRaw($query);
        }

        return $tickets->get();
    }

    public static function addTicketToViewingHistory(Ticket $ticket, User $user)
    {
        TicketsViewingHistory::updateOrCreate([
            'user_id' => $user->id,
            'ticket_id' => $ticket->id
        ], [
            'updated_at' => now()
        ]);
    }

    public static function assignTicket(Ticket $ticket, User $user)
    {
        if (!$user->isManager()) {
            throw new LogicException('User must be a manager');
        }

        if ($ticket->manager_id) {
            throw new Exception('Ticket has already assigned to another manager');
        }

        $ticket->update(['manager_id' => $user->id]);
    }

    private static function getFilterQuery(string $key, $value, User $user): ?string
    {
        switch ($key) {
            case 'closed':
                return 'is_closed = true';
            case 'not-closed':
                return 'is_closed = false';
            case 'responded':
            case 'not-responded':
                return "(
                    select is_from_manager
                    from messages
                    where ticket_id = tickets.id
                    order by created_at desc
                    limit 1
                ) = " . ($key === 'responded' ? 'true' : 'false');
            case 'viewed';
            case 'not-viewed';
                return "tickets.id " . ($key === 'not-viewed' ? 'not ': '') . "in (
                    select ticket_id
                    from tickets_viewing_history
                    where user_id = $user->id
                )";
        }

        return null;
    }
}