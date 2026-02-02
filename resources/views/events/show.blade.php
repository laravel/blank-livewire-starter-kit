@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->title }}</h1>

    @if($event->image_path)
        <img src="{{ asset('storage/'.$event->image_path) }}" alt="{{ $event->title }}" style="max-width:100%;height:auto">
    @endif

    <p><strong>Quando:</strong> {{ $event->start_at->format('d/m/Y H:i') }} @if($event->end_at) - {{ $event->end_at->format('d/m/Y H:i') }} @endif</p>
    <p><strong>Onde:</strong> {{ $event->location }}</p>
    <p>{{ $event->description }}</p>
    <p><strong>Organizador:</strong> {{ $event->organizer_name }} ({{ $event->organizer_email }})</p>
</div>
@endsection
