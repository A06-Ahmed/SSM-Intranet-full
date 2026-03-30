@extends('admin.layout')

@section('content')
  <div class="card">
    <div class="actions" style="justify-content: space-between; align-items: center;">
      <h2>Annonces</h2>
      <a class="btn" href="{{ route('admin.announcements.create') }}">Nouvelle annonce</a>
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
        @foreach($announcements as $announcement)
          <tr>
            <td>{{ $announcement->title }}</td>
            <td>{{ $announcement->author?->first_name }} {{ $announcement->author?->last_name }}</td>
            <td>{{ $announcement->is_published ? 'Publié' : 'Brouillon' }}</td>
            <td>{{ $announcement->created_at->format('Y-m-d') }}</td>
            <td class="actions">
              <a class="btn secondary" href="{{ route('admin.announcements.edit', $announcement) }}">Modifier</a>
              <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $announcements->links() }}
  </div>
@endsection
