@extends('admin.layout')

@section('content')
  <div class="card">
    <h2>{{ $user->exists ? 'Modifier utilisateur' : 'Nouvel utilisateur' }}</h2>
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
      @csrf
      @if($user->exists)
        @method('PUT')
      @endif
      <div class="form-group">
        <label>Prenom</label>
        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required />
      </div>
      <div class="form-group">
        <label>Nom</label>
        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required />
      </div>
      <div class="form-group">
        <label>Departement</label>
        <select name="department_id">
          <option value="">--</option>
          @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ (string) old('department_id', $user->department_id) === (string) $department->id ? 'selected' : '' }}>
              {{ $department->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Mot de passe {{ $user->exists ? '(laisser vide pour conserver)' : '' }}</label>
        <input type="password" id="user-password" name="password" />
      </div>
      <div class="form-group">
        <label>Confirmation du mot de passe</label>
        <input type="password" id="user-password-confirmation" name="password_confirmation" />
      </div>
      <div class="form-group form-check">
        <input type="checkbox" id="toggle-user-password" />
        <label for="toggle-user-password">Afficher les mots de passe</label>
      </div>
      <div class="form-group">
        <label>Statut</label>
        <select name="is_active">
          <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Actif</option>
          <option value="0" {{ old('is_active', $user->is_active) ? '' : 'selected' }}>Inactif</option>
        </select>
      </div>
      @if(auth()->user()->hasRole('SuperAdmin'))
        <div class="form-group">
          <label>Roles</label>
          <select name="roles[]" multiple>
            @foreach($roles as $role)
              <option value="{{ $role->name }}" {{ in_array($role->name, $user->roles->pluck('name')->all()) ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          <small>Super admin peut attribuer Admin, HR, Manager, Employee.</small>
        </div>
      @endif
      <button class="btn" type="submit">Enregistrer</button>
    </form>
  </div>

  <script>
    (function () {
      const toggle = document.getElementById('toggle-user-password')
      const password = document.getElementById('user-password')
      const confirmation = document.getElementById('user-password-confirmation')
      if (!toggle || !password || !confirmation) return

      toggle.addEventListener('change', function () {
        const type = toggle.checked ? 'text' : 'password'
        password.type = type
        confirmation.type = type
      })
    })()
  </script>

  @if(!$user->exists)
    <script>
      (function () {
        const form = document.querySelector('form')
        if (!form) return

        form.addEventListener('submit', async function (e) {
          e.preventDefault()

          const formData = new FormData(form)

          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
          })

          const contentType = response.headers.get('content-type') || ''

          if (!response.ok) {
            let message = 'Impossible d\'enregistrer l\'utilisateur.'
            try {
              if (contentType.includes('application/json')) {
                const data = await response.json()
                if (data?.message) message = data.message
                if (data?.errors) {
                  message = Object.values(data.errors).flat().join('\\n')
                }
              }
            } catch {
              // ignore parse errors
            }
            alert(message)
            return
          }

          if (!contentType.includes('application/json')) {
            window.location.href = response.url
            return
          }

          const payload = await response.json()
          const user = payload?.user || {}

          const proceed = window.confirm('Utilisateur enregistre avec succes. Voulez-vous l\'ajouter a l\'Annuaire ?')

          if (proceed) {
            const departmentSelect = form.querySelector('select[name=\"department_id\"]')
            const departmentName = departmentSelect?.selectedOptions?.[0]?.text || ''

            sessionStorage.setItem('pendingAnnuaire', JSON.stringify({
              first_name: user.first_name || formData.get('first_name') || '',
              last_name: user.last_name || formData.get('last_name') || '',
              email: user.email || formData.get('email') || '',
              phone: user.phone || '',
              department_id: user.department_id || formData.get('department_id') || '',
              department_name: departmentName,
            }))

            window.location.href = '{{ route('admin.annuaire') }}'
            return
          }

          window.location.href = '{{ route('admin.users.index') }}'
        })
      })()
    </script>
  @endif
@endsection
