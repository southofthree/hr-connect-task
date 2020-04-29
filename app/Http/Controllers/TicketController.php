<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\StoreRequest;
use App\Http\Requests\Ticket\RespondRequest;
use App\Services\TicketService;
use App\Ticket;
use Exception;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isManager()) {
            return view('manager.tickets.index');
        } else {
            $tickets = $user->ticketsAsClient()
                            ->selectRaw("
                                id,
                                subject,
                                is_closed,
                                (select text from messages where ticket_id = tickets.id order by created_at asc limit 1) message
                            ")
                            ->orderBy('updated_at', 'desc')
                            ->get();

            return view('tickets.index', compact('tickets'));
        }
    }

    public function show(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        $ticket->load(['messages' => function($q) {
            $q->orderBy('created_at', 'asc');
        }, 'messages.attachments', 'manager']);

        if ($user->isManager()) {
            dd('hi, manager!');
        } else {
            return view('tickets.show', compact('ticket'));
        }
    }

    public function add()
    {
        return view('tickets.add');
    }

    public function store(StoreRequest $request)
    {
        try {
            TicketService::create($request->user(), $request->validated());

            return redirect()->route('home');
        } catch (Exception $e) {
            return back();
        }
    }

    public function respond(RespondRequest $request, Ticket $ticket)
    {
        try {
            TicketService::storeResponse($ticket, $request->user(), $request->validated());

            return back();
        } catch (Exception $e) {
            return back();
        }
    }
}
