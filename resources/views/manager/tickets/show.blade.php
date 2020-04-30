@extends('layouts.app')

@section('content')
    <h1>
        {{ $ticket->subject }}
        @if (!$ticket->manager_id)
            <button onclick="document.getElementById('assign').submit()" type="button" class="btn btn-success btn-sm">
                Принять заявку
            </button>

            <form id="assign" action="{{ route('tickets.assign', $ticket) }}" method="post">
                @csrf
            </form>
        @endif
    </h1>

    @error('assign_error')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    @foreach ($ticket->messages as $message)
        @include('partials.message', [
            'is_response' => !$message->is_from_manager,
            'from' => !$message->is_from_manager ? $ticket->client->name : ($ticket->assignedTo(Auth::id()) ? 'Вы' : $ticket->manager->name),
            'time' => $message->created_at,
            'text' => nl2br($message->text),
            'attachments' => $message->attachments
        ])
    @endforeach

    @if ($ticket->is_closed)
        <div class="alert alert-info">
            Заявка закрыта
        </div>
    @elseif ($ticket->assignedTo(Auth::id()))
        @include('partials.message_form', [
            'title' => 'Ответить',
            'action' => route('tickets.respond', $ticket),
            'subject_field' => false
        ])
    @endif
@endsection
