@extends('layouts.app')

@section('content')
    @can('create', App\Ticket::class)
        @include('partials.message_form', [
            'title' => 'Новая заявка',
            'action' => route('tickets.store'),
            'subject_field' => true
        ])
    @else
        <div class="alert alert-warning">
            Извините, новую заявку можно оставлять не чаще, чем раз в сутки :(
        </div>
    @endcan
@endsection
