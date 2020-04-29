@extends('layouts.app')

@section('content')
    @can('create', App\Ticket::class)
        <div class="card">
            <div class="card-header">
                Новая заявка
            </div>

            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="subject" class="col-md-4 col-form-label text-md-right">
                            Тема
                        </label>

                        <div class="col-md-6">
                            <input id="subject" type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" required autofocus>

                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

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

                    @error('error')
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            </div>
                        </div>
                    @enderror

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
    @else
        <div class="alert alert-warning">
            Извините, новую заявку можно оставлять не чаще, чем раз в сутки :(
        </div>
    @endcan
@endsection
