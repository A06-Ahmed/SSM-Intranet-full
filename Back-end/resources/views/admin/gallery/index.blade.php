@extends('admin.layout')

@section('content')
  <div class="card">
    <div class="actions" style="justify-content: space-between; align-items: center;">
      <h2>Galerie</h2>
      @php
        $isHr = auth()->user()->hasRole('HR') || auth()->user()->hasRole('RH');
      @endphp
      @if($isHr)
        <span class="nav-disabled">Téléverser une image</span>
      @else
        <a class="btn" href="{{ route('admin.gallery.create') }}">Téléverser une image</a>
      @endif
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Images</th>
          <th>Téléversé par</th>
          <th>Créé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
          <tr>
            <td>{{ $item->title }}</td>
            <td>
              <span class="badge">{{ $item->images->count() }} image(s)</span>
            </td>
            <td>{{ $item->uploader?->first_name }} {{ $item->uploader?->last_name }}</td>
            <td>{{ $item->created_at->format('Y-m-d') }}</td>
            <td class="actions">
              <a class="btn secondary" href="{{ route('admin.gallery.edit', $item) }}">Modifier</a>
              <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $items->links() }}
  </div>
@endsection
