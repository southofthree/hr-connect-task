@extends('layouts.app')

@section('content')
    <h1>
        {{ $ticket->subject }}
        @if (!$ticket->is_closed)
            <button onclick="document.getElementById('close').submit()" type="button" class="btn btn-primary btn-sm">
                Закрыть заявку
            </button>

            <form id="close" action="{{ route('tickets.close', $ticket) }}" method="post">
                @csrf
            </form>
        @endif
    </h1>

    @foreach ($ticket->messages as $message)
        @include('partials.message', [
            'is_response' => $message->is_from_manager,
            'from' => !$message->is_from_manager ? 'Вы' : $ticket->manager->name,
            'time' => $message->created_at,
            'text' => nl2br($message->text),
            'attachments' => $message->attachments
        ])
    @endforeach

    @if ($ticket->is_closed)
        <div class="alert alert-info">
            Заявка закрыта
        </div>
    @elseif ($ticket->messages->last()->is_from_manager)
        @include('partials.message_form', [
            'title' => 'Ответить',
            'action' => route('tickets.respond', $ticket),
            'subject_field' => false
        ])
    @endif
@endsection
