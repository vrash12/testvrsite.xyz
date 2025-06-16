<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin • PatientCare</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Font Awesome --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body { margin:0; padding:0; height:100vh; overflow:hidden; }
    .sidebar {
      background-color: #343a40;
      color: white;
      width: 240px;
      height: 100vh;
      position: fixed;
      display: flex; flex-direction: column; justify-content: space-between;
    }
    .sidebar .top-section {
      padding: 1.5rem 1rem;
      text-align: center;
    }
    .sidebar .logo {
      width: 80px; margin-bottom: 1rem;
    }
    .sidebar .avatar {
      width: 90px; height: 90px; border-radius:50%;
      background-color: #6c757d; margin: 0 auto 1rem;
    }
    .sidebar .name {
      font-weight: bold; font-size: 1rem;
    }
    .sidebar .id-label {
      font-size: 0.875rem; color: #adb5bd;
    }
    .sidebar nav ul {
      list-style: none; padding: 0; margin: 2rem 0;
    }
    .sidebar nav ul li {
      margin: 0.5rem 0;
    }
    .sidebar nav a {
      display: flex; align-items: center; gap: 10px;
      color: white; text-decoration: none;
      padding: 0.5rem 1.25rem;
      transition: background 0.3s;
    }
    .sidebar nav a.active,
    .sidebar nav a:hover {
      background-color: rgba(255,255,255,0.1);
    }
    .sidebar .footer {
      font-size: 0.75rem; text-align: center; padding: 1rem;
      border-top:1px solid rgba(255,255,255,0.2);
    }
    .main-content {
      margin-left: 240px; padding: 2rem; height: 100vh; overflow-y: auto;
    }
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="top-section">
      <img src="{{ asset('images/patientcare-logo-white.png') }}" alt="Logo" class="logo">
      <div class="avatar"></div>
      <div class="name">{{ Auth::user()->username ?? 'Admin User' }}</div>
      <div class="id-label">User ID: {{ Auth::id() }}</div>
    </div>

    <nav>
      <ul>
        <li>
          <a href="{{ route('admin.dashboard') }}"
             class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="{{ route('admin.users.index') }}"
             class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Manage Users
          </a>
        </li>
      </ul>
    </nav>

    <div class="footer">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-link text-danger w-100 text-start">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
      PatientCare © {{ date('Y') }}<br>
      Version 1.0.0
    </div>
  </aside>

  <main class="main-content">
    @yield('content')
  </main>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 