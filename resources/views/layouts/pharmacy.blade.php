{{-- resources/views/layouts/pharmacy.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy • PatientCare</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {{-- CSS --}}
    @vite('resources/css/app.css')

</head>
<body>
  <aside class="sidebar">
    <div class="top-section">
      <img src="{{ asset('images/patientcare-logo-white.png') }}" alt="Logo" class="logo">
      <div class="avatar"></div>
      <div class="name">{{ Auth::user()->username ?? 'Pharmacist User' }}</div>
      <div class="id-label">Pharmacist ID no. {{ Auth::id() }}</div>
    </div>

    <nav>
      <ul>
        <li>
          <a href="{{ route('pharmacy.dashboard') }}">
            <i class="fas fa-home"></i> Home
          </a>
        </li>
       <li>
         <a href="{{ route('pharmacy.medicines.index') }}">
           <i class="fas fa-medkit"></i> Medicines
         </a>
       </li>
        <li>
          <a href="{{ route('pharmacy.charges.create') }}">
            <i class="fas fa-pills"></i> New Medication Charge
          </a>
        </li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="btn text-white w-100 text-start"
                    style="background:none; border:none;">
              <i class="fas fa-sign-out-alt"></i> Logout
            </button>
          </form>
        </li>
      </ul>
    </nav>

    <div class="footer">
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
