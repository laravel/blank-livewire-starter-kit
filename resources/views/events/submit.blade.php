@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Submeter evento</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Data/Inicio</label>
            <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Data/Fim</label>
            <input type="datetime-local" name="end_at" value="{{ old('end_at') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Local</label>
            <input type="text" name="location" value="{{ old('location') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Seu nome</label>
            <input type="text" name="organizer_name" value="{{ old('organizer_name') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Seu e-mail</label>
            <input type="email" name="organizer_email" value="{{ old('organizer_email') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone de contato</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem (opcional, max 5MB)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-primary">Submeter evento</button>
    </form>
</div>
@endsection
