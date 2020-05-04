@extends('layouts.app')

@section('content')
    <form action="{{ route('home') }}" method="get">
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" {{ request()->input('viewed') ? 'checked' : '' }} type="checkbox" id="viewed" name="viewed" value="true">
                <label class="form-check-label" for="viewed">
                    Просмотренные
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" {{ request()->input('not-viewed') ? 'checked' : '' }} type="checkbox" id="not-viewed" name="not-viewed" value="true">
                <label class="form-check-label" for="not-viewed">
                    Непросмотренные
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" {{ request()->input('closed') ? 'checked' : '' }} type="checkbox" id="closed" name="closed" value="true">
                <label class="form-check-label" for="closed">
                    Закрытые
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" {{ request()->input('not-closed') ? 'checked' : '' }} type="checkbox" id="not-closed" name="not-closed" value="true">
                <label class="form-check-label" for="not-closed">
                    Незакрытые
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" {{ request()->input('responded') ? 'checked' : '' }} type="checkbox" id="responded" name="responded" value="true">
                <label class="form-check-label" for="responded">
                    С ответом
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" {{ request()->input('not-responded') ? 'checked' : '' }} type="checkbox" id="not-responded" name="not-responded" value="true">
                <label class="form-check-label" for="not-responded">
                    Без ответа
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">
            Применить фильтр
        </button>
    </form>

    <br>

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

    {{ $tickets->appends(request()->query())->links() }}
@endsection
