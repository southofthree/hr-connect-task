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
                            {{ $ticket->subject }}
                        </strong>
                    </th>
                    <td>
                        {{ $ticket->is_closed ? 'Закрыта' : 'Открыта' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
