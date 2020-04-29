<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\StoreRequest;
use App\Services\TicketService;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isManager()) {
            return view('manager.tickets.index');
        } else {
            $tickets = $user->ticketsAsClient()
                            ->orderBy('updated_at', 'desc')
                            ->get();

            return view('tickets.index', compact('tickets'));
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
}
