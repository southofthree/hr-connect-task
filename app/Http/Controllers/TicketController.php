<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\StoreRequest;
use App\Http\Requests\Ticket\RespondRequest;
use App\Http\Requests\Ticket\CloseRequest;
use App\Services\TicketService;
use App\Ticket;
use Exception;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isManager()) {
            $tickets = TicketService::getTickets($request->all(), $user);

            $view = 'manager.tickets.index';
        } else {
            $tickets = TicketService::getClientTickets($user);

            $view = 'tickets.index';
        }

        return view($view, compact('tickets'));
    }

    public function show(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        TicketService::addTicketToViewingHistory($ticket, $user);

        $ticket->load(['messages' => function($q) {
            $q->orderBy('created_at', 'asc');
        }, 'messages.attachments', 'manager']);

        $view = $user->isManager() ? 'manager.tickets.show' : 'tickets.show';

        return view($view, compact('ticket'));
    }

    public function add()
    {
        return view('tickets.add');
    }

    public function store(StoreRequest $request)
    {
        try {
            TicketService::createTicket($request->user(), $request->validated());

            return redirect()->route('home');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput($request->all());
        }
    }

    public function respond(RespondRequest $request, Ticket $ticket)
    {
        try {
            TicketService::storeResponse($ticket, $request->user(), $request->validated());

            return back();
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput($request->all());
        }
    }

    public function close(CloseRequest $request, Ticket $ticket)
    {
        $ticket->close();

        return back();
    }

    public function assign(Request $request, Ticket $ticket)
    {
        try {
            TicketService::assignTicket($ticket, $request->user());

            return back();
        } catch (Exception $e) {
            return back()->withErrors(['assign_error' => $e->getMessage()]);
        }
    }
}
