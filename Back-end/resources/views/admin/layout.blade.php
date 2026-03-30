<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administration SMM Intranet</title>
  <style>
    :root {
      --bg: #f5f7fb;
      --card: #ffffff;
      --text: #102a43;
      --muted: #627d98;
      --primary: #0b66d0;
      --danger: #c53030;
      --border: #e2e8f0;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    header {
      background: var(--card);
      border-bottom: 1px solid var(--border);
      padding: 16px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .container {
      display: grid;
      grid-template-columns: 240px 1fr;
      min-height: calc(100vh - 66px);
    }
    nav {
      background: var(--card);
      border-right: 1px solid var(--border);
      padding: 24px 16px;
    }
    nav a {
      display: block;
      padding: 10px 12px;
      color: var(--text);
      text-decoration: none;
      border-radius: 8px;
      margin-bottom: 6px;
    }
    nav a.active, nav a:hover { background: #e6f0ff; color: var(--primary); }
    .nav-disabled {
      display: block;
      padding: 10px 12px;
      color: #94a3b8;
      background: #f1f5f9;
      border-radius: 8px;
      margin-bottom: 6px;
      cursor: not-allowed;
    }
    main { padding: 24px; }
    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .admin-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px;
    }
    .admin-form-input {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--border);
      border-radius: 6px;
    }
    .chart-container {
      height: 220px;
      margin: 0 auto;
      width: 100%;
    }
    .admin-footer {
      margin-top: 24px;
      text-align: center;
      font-size: 11px;
      color: rgba(15, 23, 42, 0.5);
    }
    .btn {
      background: var(--primary);
      color: #fff;
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
    }
    .btn.secondary { background: #64748b; }
    .btn.danger { background: var(--danger); }
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    .table th, .table td {
      padding: 10px;
      border-bottom: 1px solid var(--border);
      text-align: left;
    }
    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 999px;
      background: #e2e8f0;
      font-size: 12px;
    }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; margin-bottom: 6px; color: var(--muted); }
    .checkbox-custom { width: 16px!important; }
    .form-group input, .form-group textarea, .form-group select {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--border);
      border-radius: 6px;
    }
    .form-check {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: var(--text);
    }
    .form-check input {
      width: auto;
      margin: 0;
    }
    .form-check-group {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }
    .checkbox-wrapper {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      user-select: none;
      color: var(--text);
    }
    .checkbox-custom {
      width: 16px;
      height: 16px;
      border: 1px solid var(--border);
      border-radius: 4px;
      appearance: none;
      display: inline-block;
      position: relative;
      background: #fff;
    }
    .checkbox-custom:checked {
      background: var(--primary);
      border-color: var(--primary);
    }
    .checkbox-custom:checked::after {
      content: '';
      position: absolute;
      left: 4px;
      top: 1px;
      width: 4px;
      height: 8px;
      border: solid #fff;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
    .flash { padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; }
    .flash.success { background: #e6fffa; color: #047857; }
    .flash.error { background: #fff5f5; color: #c53030; }
    .actions { display: flex; gap: 8px; }
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 16px;
    }
    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
      padding: 24px;
    }
    .confirm-modal-overlay {
      position: fixed;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      inset: 0;
    }
    @media (max-width: 900px) {
      .container {
        grid-template-columns: 1fr;
      }
      nav {
        border-right: none;
        border-bottom: 1px solid var(--border);
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
      }
      nav a {
        margin-bottom: 0;
      }
      header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      .chart-container {
        height: 200px;
      }
    }

    @media (max-width: 700px) {
      header {
        padding: 12px 16px;
      }

      main {
        padding: 16px;
      }

      .card {
        padding: 16px;
      }

      .table {
        display: block;
        width: 100%;
        overflow-x: auto;
      }

      .table th,
      .table td {
        white-space: nowrap;
      }
    }
  </style>
</head>
<body>
  <header>
    <div>
      <strong>Administration SMM Intranet</strong>
    </div>
    <div>
      <span>{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</span>
      <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn secondary" style="margin-left:12px">Déconnexion</button>
      </form>
    </div>
  </header>
  <div class="container">
    <nav>
      @php
        $user = auth()->user();
        $isSuper = $user->hasRole('SuperAdmin');
        $isAdmin = $user->hasRole('Admin');
        $isManager = $user->hasRole('Manager');
        $isHr = $user->hasRole('HR') || $user->hasRole('RH');
        $canUsers = $isSuper || $isAdmin || $isHr;
        $canAnnuaire = $isSuper || $isAdmin || $isHr;
        $canDepartments = $isSuper || $isAdmin || $isHr;
        $canAnnouncements = $isSuper || $isAdmin || $isHr;
        $canNews = $isSuper || $isAdmin || $isManager;
        $canGallery = $isSuper || $isAdmin || $isManager;
      @endphp

      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Tableau de bord</a>

      @if($canUsers)
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Utilisateurs</a>
      @else
        <span class="nav-disabled">Utilisateurs</span>
      @endif

      @if($canAnnuaire)
        <a href="{{ route('admin.annuaire') }}" class="{{ request()->routeIs('admin.annuaire') ? 'active' : '' }}">Annuaire</a>
        <a href="{{ route('admin.annuaire.list') }}" class="{{ request()->routeIs('admin.annuaire.list') ? 'active' : '' }}">Annuaire (liste)</a>
      @else
        <span class="nav-disabled">Annuaire</span>
        <span class="nav-disabled">Annuaire (liste)</span>
      @endif

      @if($canDepartments)
        <a href="{{ route('admin.departments.index') }}" class="{{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">Départements</a>
      @else
        <span class="nav-disabled">Départements</span>
      @endif

      @if($canAnnouncements)
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">Annonces</a>
      @else
        <span class="nav-disabled">Annonces</span>
      @endif

      @if($canNews)
        <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">Actualités</a>
      @else
        <span class="nav-disabled">Actualités</span>
      @endif

      @if($canGallery)
        <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Galerie</a>
      @else
        <span class="nav-disabled">Galerie</span>
      @endif
    </nav>
    <main>
      @if(session('success'))
        <div class="flash success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="flash error">
          <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @yield('content')
      <footer class="admin-footer">© 2026 SMM SOCODAM DAVUM</footer>
    </main>
  </div>
</body>
</html>
