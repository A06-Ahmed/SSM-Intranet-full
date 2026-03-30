@extends('admin.layout')

@section('content')
  <div class="card">
    <div class="actions" style="justify-content: space-between; align-items: center;">
      <h2>Départements</h2>
    </div>

    @php
      $roles = auth()->user()->roles()->pluck('name')->all();
      $canManage = in_array('SuperAdmin', $roles, true) || in_array('Admin', $roles, true) || in_array('HR', $roles, true);
    @endphp

    @if($canManage)
      <div class="admin-card" style="margin-bottom:16px;">
        <h3>Créer un département</h3>
        <form method="POST" action="{{ route('admin.departments.store') }}">
          @csrf
          <div class="form-group">
            <label>Nom</label>
            <input class="admin-form-input" type="text" name="name" required />
          </div>
          <div class="form-group">
            <label>Description</label>
            <input class="admin-form-input" type="text" name="description" />
          </div>
          <button class="btn" type="submit">Créer</button>
        </form>
      </div>
    @endif

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        @foreach($departments as $department)
          <tr>
            <td>{{ $department->id }}</td>
            <td>{{ $department->name }}</td>
            <td>{{ $department->description }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
