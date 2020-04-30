<div class="card card--message {{ $is_response ? 'card--response' : '' }}">
    <div class="card-header">
        {{ $from }}, {{ $time }}
    </div>

    <div class="card-body">
        {!! $text !!}

        @if ($attachments->count())
            <br><br>
            <strong>
                Прикрепленные файлы:
            </strong>

            @foreach ($attachments as $index => $attachment)
                <br>
                <a href="{{ Storage::disk('public')->url($attachment->filename) }}" download>
                    {{ $attachment->filename }}
                </a>
            @endforeach
        @endif
    </div>
</div>