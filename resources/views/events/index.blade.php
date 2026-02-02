@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Eventos</h1>

    @foreach($events as $event)
        <article style="margin-bottom:1.5rem">
            <h2><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></h2>
            <p>{{ $event->start_at->format('d/m/Y H:i') }} @if($event->end_at) - {{ $event->end_at->format('d/m/Y H:i') }} @endif</p>
            <p>{{ $event->location }}</p>
        </article>
    @endforeach

    {{ $events->links() }}
</div>
@endsection
