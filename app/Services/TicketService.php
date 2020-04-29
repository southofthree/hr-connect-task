<?php

namespace App\Services;

use DB;
use Exception;
use App\User;
use App\Ticket;

class TicketService
{
    public static function create(User $client, array $data): Ticket
    {
        try {
            DB::beginTransaction();

            $ticket = $client->ticketsAsClient()->create($data);
            $message = $ticket->messages()->create(['text' => $data['message']]);
            
            if (isset($data['attachment'])) {
                $filename =  str_replace('public/', '', $data['attachment']->store('public'));

                $message->attachments()->create(['filename' => $filename]);
            }

            DB::commit();

            return $ticket;
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }
}