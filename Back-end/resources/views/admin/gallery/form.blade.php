@extends('admin.layout')

@section('content')
  <div class="card">
    <h2>{{ $item->exists ? 'Modifier la galerie' : 'Televerser une galerie' }}</h2>
    <form method="POST" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}">
      @csrf
      @if($item->exists)
        @method('PUT')
      @endif
      <div class="form-group">
        <label>Titre</label>
        <input type="text" name="title" value="{{ old('title', $item->title) }}" required />
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description', $item->description) }}</textarea>
      </div>
      <div class="form-group">
        <label>Images</label>
        <input type="file" name="images[]" accept="image/png,image/jpeg,image/webp" multiple {{ $item->exists ? '' : 'required' }} />
        <small>Vous pouvez selectionner plusieurs images pour un meme album.</small>
      </div>
      <button class="btn" type="submit">Enregistrer</button>
    </form>
  </div>
@endsection

