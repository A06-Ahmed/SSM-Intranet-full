@extends('admin.layout')

@section('content')
  <div class="card">
    <div class="actions" style="justify-content: space-between; align-items: center;">
      <h2>Utilisateurs</h2>
      <a class="btn" href="{{ route('admin.users.create') }}">Nouvel utilisateur</a>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôles</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @foreach($user->roles as $role)
                <span class="badge">{{ $role->name }}</span>
              @endforeach
            </td>
            <td>{{ $user->is_active ? 'Actif' : 'Inactif' }}</td>
            <td class="actions">
              <a class="btn secondary" href="{{ route('admin.users.edit', $user) }}">Modifier</a>
              <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $users->links() }}
  </div>
@endsection
