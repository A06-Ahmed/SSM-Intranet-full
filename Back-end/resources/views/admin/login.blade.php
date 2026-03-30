<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion Admin SMM Intranet</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; 
      background: linear-gradient(6deg,rgba(3, 0, 69, 1) 0%, rgba(43, 43, 138, 1) 100%); display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
    .card { background:#fff; padding:32px; border-radius:12px; border:1px solid #e2e8f0; width:360px; }
    .form-group { margin-bottom: 14px; }
    label { display:block; margin-bottom:6px; color:#64748b; }
    input { width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; }
    .btn { background: #030045;; color:#fff; padding:10px 14px; border:none; border-radius:6px; width:100%; cursor:pointer; }
    .error { color:#c53030; margin-bottom: 10px; }
    .form-group-check { display:flex; align-items:center; margin-bottom: 14px; }
    .form-group-check input { width:auto; margin-left:10px; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Connexion administrateur</h2>
    @if($errors->any())
      <div class="error">
        {{ $errors->first() }}
      </div>
    @endif
    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required />
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required />
      </div>
      <div class="form-group-check">
        <label>Se souvenir de moi</label>
        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
      </div>
      <button type="submit" class="btn">Se connecter</button>
    </form>
  </div>
</body>
</html>
