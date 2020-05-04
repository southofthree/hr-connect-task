@extends('layouts.app')

@section('content')
    <a href="{{ route('tickets.add') }}" class="btn btn-primary">
        Новая заявка
    </a>

    <br><br>

    <table class="table">
        <thead>
            <tr>
                <th>
                    Заявка
                </th>
                <th>
                    Статус
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                @if ($ticket->manager_last_message_text
                    && (
                            $ticket->last_viewed_at === null ||
                            $ticket->last_viewed_at <= $ticket->manager_last_message_created_at
                        )
                    )
                    <tr style="background-color: #F1F8E9">
                @else
                    <tr>
                @endif
                    <td>
                        <strong>
                            <a href="{{ route('tickets.show', $ticket) }}">
                                {{ $ticket->subject }}
                            </a>
                            <p>
                                {{ $ticket->client_first_message }}
                            </p>
                            @if ($ticket->manager_last_message_text)
                                <div class="alert alert-info">
                                    {{ $ticket->manager_last_message_text }}
                                </div>
                            @endif
                        </strong>
                    </td>
                    <td>
                        {!! $ticket->is_closed ? '✔ Закрыта' : '<span style="color: green">Открыта</span>' !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tickets->links() }}
@endsection
