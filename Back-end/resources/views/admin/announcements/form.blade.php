@extends('admin.layout')

@section('content')
  <div class="card">
    <h2>{{ $announcement->exists ? 'Modifier l\'annonce' : 'Nouvelle annonce' }}</h2>
    <form method="POST" action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
      @csrf
      @if($announcement->exists)
        @method('PUT')
      @endif
      <div class="form-group">
        <label>Titre</label>
        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required />
      </div>
      <div class="form-group">
        <label>Contenu</label>
        <textarea name="content" rows="6" maxlength="255" required>{{ old('content', $announcement->content) }}</textarea>
        <small>255 caracteres maximum.</small>
      </div>
      <div class="form-group">
        <label>Priorité</label>
        <div class="form-check-group">
          @php($priority = old('priority_status', $announcement->priority_status ?? 'Moyenne'))
          <label class="form-check">
            <input type="radio" name="priority_status" value="Moyenne" {{ $priority === 'Moyenne' ? 'checked' : '' }} />
            Moyenne
          </label>
          <label class="form-check">
            <input type="radio" name="priority_status" value="Haute" {{ $priority === 'Haute' ? 'checked' : '' }} />
            Haute
          </label>
        </div>
      </div>
      <div class="form-group">
        <label class="checkbox-wrapper">
          <input class="checkbox-custom" type="checkbox" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }} />
          Publier maintenant
        </label>
      </div>
      <button class="btn" type="submit">Enregistrer</button>
    </form>
  </div>
@endsection
