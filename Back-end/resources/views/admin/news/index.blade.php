@extends('admin.layout')

@section('content')
  <div class="card">
    <div class="actions" style="justify-content: space-between; align-items: center;">
      <h2>Actualités</h2>
      @php
        $isHr = auth()->user()->hasRole('HR') || auth()->user()->hasRole('RH');
      @endphp
      @if($isHr)
        <span class="nav-disabled">Nouvelle actualité</span>
      @else
        <a class="btn" href="{{ route('admin.news.create') }}">Nouvelle actualité</a>
      @endif
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Auteur</th>
          <th>Statut</th>
          <th>Créé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($newsItems as $news)
          <tr>
            <td>{{ $news->title }}</td>
            <td>{{ $news->author?->first_name }} {{ $news->author?->last_name }}</td>
            <td>{{ $news->is_published ? 'Publié' : 'Brouillon' }}</td>
            <td>{{ $news->created_at->format('Y-m-d') }}</td>
            <td class="actions">
              <a class="btn secondary" href="{{ route('admin.news.edit', $news) }}">Modifier</a>
              <form method="POST" action="{{ route('admin.news.destroy', $news) }}">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $newsItems->links() }}
  </div>
@endsection
