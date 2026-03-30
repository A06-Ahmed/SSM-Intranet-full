@extends('admin.layout')

@section('content')
  <div class="card">
    <h2>Annuaire - Liste</h2>
    <form method="GET" action="{{ route('admin.annuaire.list') }}" style="margin: 12px 0 18px;">
      <div class="form-group">
        <label>Recherche</label>
        <input
          type="text"
          name="q"
          value="{{ $query }}"
          placeholder="Nom, email, téléphone, département..."
          class="admin-form-input"
        />
      </div>
      <button type="submit" class="btn">Rechercher</button>
      <a href="{{ route('admin.annuaire.list') }}" class="btn secondary">Réinitialiser</a>
    </form>

    <div style="margin-bottom: 10px; color: #64748b;">
      {{ $contacts->count() }} résultat(s)
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Type</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Affectation / Organisation</th>
          <th>Département</th>
        </tr>
      </thead>
      <tbody>
        @forelse($contacts as $contact)
          <tr>
            <td>{{ $contact['name'] ?? '' }}</td>
            <td><span class="badge">{{ $contact['type'] ?? '' }}</span></td>
            <td>{{ $contact['email'] ?? '' }}</td>
            <td>{{ $contact['phone'] ?? '' }}</td>
            <td>{{ $contact['affectation'] ?? '' }}</td>
            <td>{{ $contact['department'] ?? '' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6">Aucun contact trouvé.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

