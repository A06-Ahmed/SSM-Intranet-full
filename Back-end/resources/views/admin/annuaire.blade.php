@extends('admin.layout')

@section('content')
  <div class="card">
    <h2>Annuaire - Ajout manuel</h2>
    <form method="POST" action="{{ route('admin.annuaire.store') }}">
      @csrf
      <div class="form-group">
        <label>Prénom</label>
        <input type="text" id="annuaire-first-name" name="first_name" value="{{ old('first_name', $prefill['first_name'] ?? '') }}" required />
      </div>
      <div class="form-group">
        <label>Nom</label>
        <input type="text" id="annuaire-last-name" name="last_name" value="{{ old('last_name', $prefill['last_name'] ?? '') }}" required />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" id="annuaire-email" name="email" value="{{ old('email', $prefill['email'] ?? '') }}" required />
      </div>
      <div class="form-group">
        <label>Département</label>
        <select id="annuaire-department" name="department_id">
          <option value="">--</option>
          @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ (string) old('department_id', $prefill['department_id'] ?? '') === (string) $department->id ? 'selected' : '' }}>
              {{ $department->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Matricule</label>
        <input type="text" name="matricule" required />
      </div>
      <div class="form-group">
        <label>Poste</label>
        <input type="text" name="position" />
      </div>
      <div class="form-group">
        <label>Téléphone</label>
        <input type="text" id="annuaire-phone" name="phone" />
      </div>
      <div class="form-group">
        <label>Lieu de bureau</label>
        <input type="text" name="office_location" />
      </div>
      <div class="form-group">
        <label>Date d'embauche</label>
        <input type="date" name="hire_date" />
      </div>
      <div class="form-group">
        <label>Statut</label>
        <select name="status">
          <option value="active">Actif</option>
          <option value="inactive">Inactif</option>
          <option value="on_leave">En congé</option>
        </select>
      </div>
      <button class="btn" type="submit">Ajouter</button>
    </form>
  </div>

  <div class="card">
    <h2>Annuaire - Import Excel/CSV</h2>
    <p>Le fichier doit contenir une ligne d'en-tête avec ces colonnes :</p>
    <code>first_name,last_name,email,department_id,matricule,position,phone,office_location,hire_date,status</code>
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.annuaire.import') }}" style="margin-top:16px;">
      @csrf
      <div class="form-group">
        <label>Fichier (.xlsx, .xlsm ou .csv)</label>
        <input type="file" name="file" accept=".xlsx,.xlsm,.csv" required />
      </div>
      <button class="btn" type="submit">Importer</button>
    </form>
  </div>

  <script>
    (function () {
      const stored = sessionStorage.getItem('pendingAnnuaire')
      if (!stored) return

      let data = null
      try {
        data = JSON.parse(stored)
      } catch {
        sessionStorage.removeItem('pendingAnnuaire')
        return
      }

      if (!data) return

      const firstName = document.getElementById('annuaire-first-name')
      const lastName = document.getElementById('annuaire-last-name')
      const email = document.getElementById('annuaire-email')
      const phone = document.getElementById('annuaire-phone')
      const department = document.getElementById('annuaire-department')

      if (firstName && data.first_name) firstName.value = data.first_name
      if (lastName && data.last_name) lastName.value = data.last_name
      if (email && data.email) email.value = data.email
      if (phone && data.phone) phone.value = data.phone
      if (department && data.department_id) department.value = data.department_id

      sessionStorage.removeItem('pendingAnnuaire')
    })()
  </script>
@endsection
