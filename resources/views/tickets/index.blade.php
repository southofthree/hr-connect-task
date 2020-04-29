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
                <tr>
                    <th>
                        <strong>
                            <a href="{{ route('tickets.show', $ticket) }}">
                                {{ $ticket->subject }}
                            </a>
                            <p>
                                {{ $ticket->message }}
                            </p>
                        </strong>
                    </th>
                    <td>
                        {!! $ticket->is_closed ? '✔ Закрыта' : '<span style="color: green">Открыта</span>' !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
