@extends('admin.layout')

@section('content')
  <style>
    
  </style>
  <div class="card">
    <h2>{{ $news->exists ? 'Modifier l\'actualité' : 'Nouvelle actualité' }}</h2>
    <form method="POST" enctype="multipart/form-data" action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}">
      @csrf
      @if($news->exists)
        @method('PUT')
      @endif
      <div class="form-group">
        <label>Titre</label>
        <input type="text" name="title" value="{{ old('title', $news->title) }}" required />
      </div>
      <div class="form-group">
        <label>Contenu</label>
        <textarea name="content" rows="6" required>{{ old('content', $news->content) }}</textarea>
      </div>
      <div class="form-group">
        <label>Image</label>
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp" />
        @php
          $newsImagePath = $news->image_url ? (parse_url($news->image_url, PHP_URL_PATH) ?: $news->image_url) : null;
          $newsImageName = $newsImagePath ? basename($newsImagePath) : null;
          $newsImageUrl = $newsImageName ? url('/news-image/'.$newsImageName) : null;
        @endphp
        @if($newsImageUrl)
          <div style="margin-top:6px;">
            <a href="{{ $newsImageUrl }}" target="_blank" rel="noopener noreferrer">Voir l'image actuelle</a>
          </div>
        @endif
      </div>
      <div class="form-group">
        <label class="checkbox-wrapper">
          <input class="checkbox-custom" type="checkbox" name="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }} />
          Publier maintenant
        </label>
      </div>
      <button class="btn" type="submit">Enregistrer</button>
    </form>
  </div>
@endsection
