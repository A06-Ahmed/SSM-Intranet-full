<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Import Contacts</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; background: #f5f7fb; padding: 32px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; max-width: 520px; }
    .form-group { margin-bottom: 16px; }
    label { display: block; margin-bottom: 6px; color: #4b5563; }
    input { width: 100%; }
    .btn { background: #0b66d0; color: #fff; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; }
    .flash { margin-bottom: 12px; color: #047857; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Import Contacts (.xlsx / .xlsm)</h2>
    @if(session('success'))
      <div class="flash">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="flash" style="color:#c53030;">{{ $errors->first() }}</div>
    @endif
    <form method="POST" enctype="multipart/form-data" action="{{ route('contacts.import') }}">
      @csrf
      <div class="form-group">
        <label>Excel file</label>
        <input type="file" name="file" accept=".xlsx,.xlsm" required />
      </div>
      <button class="btn" type="submit">Import</button>
    </form>
  </div>
</body>
</html>
