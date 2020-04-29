@extends('layouts.app')

@section('content')
    <h1>
        {{ $ticket->subject }}
    </h1>

    @foreach ($ticket->messages as $message)
        <div class="card card--message {{ $message->is_from_manager ? 'card--response' : '' }}">
            <div class="card-header">
                @if ($message->is_from_manager)
                    {{ $ticket->manager->name }}, {{ $message->created_at }}
                @else
                    Вы, {{ $message->created_at }}
                @endif
            </div>

            <div class="card-body">
                {!! nl2br($message->text) !!}

                @if ($message->attachments->count())
                    <br><br>
                    <strong>
                        Прикрепленные файлы:
                    </strong>

                    @foreach ($message->attachments as $index => $attachment)
                        <br>
                        <a href="{{ Storage::disk('public')->url($attachment->filename) }}" download>
                            {{ $attachment->filename }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach

    @if ($ticket->is_closed)
        <div class="alert alert-info">
            Заявка закрыта
        </div>
    @elseif ($ticket->messages->last()->is_from_manager)
        <div class="card">
            <div class="card-header">
                Ответить
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('tickets.respond', $ticket) }}">
                    @csrf

                    <div class="form-group row">
                        <label for="message" class="col-md-4 col-form-label text-md-right">
                            Сообщение
                        </label>

                        <div class="col-md-6">
                            <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" required>{{ old('message') }}</textarea>

                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="attachment" class="col-md-4 col-form-label text-md-right">
                            Файл
                        </label>

                        <div class="col-md-6">
                            <input type="file" id="attachment" name="attachment" value="{{ old('attachment') }}">

                            @error('attachment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $attachment }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Отправить
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
