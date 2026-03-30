@extends('admin.layout')

@section('content')
  <div class="card">
    <h2>Tableau de bord</h2>
    <p>Bienvenue sur le panneau d'administration SMM Intranet. Utilisez la navigation pour gérer vos modules.</p>
  </div>
  <div class="card">
    <h3>Analytique</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
      <div class="card">
        <div>Total utilisateurs</div>
        <strong>{{ $totalUsers }}</strong>
      </div>
      <div class="card">
        <div>Total actualités</div>
        <strong>{{ $totalNews }}</strong>
      </div>
      <div class="card">
        <div>Total annonces</div>
        <strong>{{ $totalAnnouncements }}</strong>
      </div>
      <div class="card">
        <div>Total images galerie</div>
        <strong>{{ $totalGalleryItems }}</strong>
      </div>
    </div>
    <div class="dashboard-grid" style="margin-top:24px;">
      <div class="card">
        <h4>Répartition des priorités</h4>
        <div class="chart-container">
          <canvas id="admin-priority-chart" height="180"></canvas>
        </div>
      </div>
      <div class="card">
        <h4>Effectif par département</h4>
        <div class="chart-container">
          <canvas id="admin-department-chart" height="180"></canvas>
        </div>
      </div>
      <div class="card">
        <h4>Activité (30 jours)</h4>
        <div class="chart-container">
          <canvas id="admin-activity-chart" height="180"></canvas>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    (function () {
      if (!window.Chart) return

      fetch('{{ url('/api/admin/dashboard-stats') }}', {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin',
      })
        .then((res) => res.json())
        .then((payload) => {
          const priorityCanvas = document.getElementById('admin-priority-chart')
          const departmentCanvas = document.getElementById('admin-department-chart')
          const activityCanvas = document.getElementById('admin-activity-chart')

          if (priorityCanvas) {
            new Chart(priorityCanvas.getContext('2d'), {
              type: 'pie',
              data: {
                labels: ['Moyenne', 'Haute'],
                datasets: [{
                  data: [
                    payload?.priority?.Moyenne ?? 0,
                    payload?.priority?.Haute ?? 0,
                  ],
                  backgroundColor: ['#3b82f6', '#ef4444'],
                }]
              },
              options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
              }
            })
          }

          if (departmentCanvas) {
            const departments = payload?.departments || []
            const labels = departments.map((d) => d.name)
            const data = departments.map((d) => d.total)

            new Chart(departmentCanvas.getContext('2d'), {
              type: 'bar',
              data: {
                labels,
                datasets: [{
                  label: 'Effectif',
                  data,
                  backgroundColor: '#14b8a6',
                  borderRadius: 8,
                }]
              },
              options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                  y: { beginAtZero: true, ticks: { precision: 0 } }
                }
              }
            })
          }

          if (activityCanvas) {
            new Chart(activityCanvas.getContext('2d'), {
              type: 'bar',
              data: {
                labels: ['Actualités', 'Annonces', 'Galerie'],
                datasets: [{
                  label: '30 derniers jours',
                  data: [
                    payload?.activity?.news ?? 0,
                    payload?.activity?.announcements ?? 0,
                    payload?.activity?.gallery ?? 0,
                  ],
                  backgroundColor: ['#2563eb', '#0ea5e9', '#f59e0b'],
                  borderRadius: 8,
                }]
              },
              options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                  y: { beginAtZero: true, ticks: { precision: 0 } }
                }
              }
            })
          }
        })
        .catch(() => {})
    })()
  </script>
@endsection
