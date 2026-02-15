<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo_book.png') }}">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<style>
  /* Make navbar links bigger with padding and nice hover */
  .navbar-nav .nav-link {
    padding: 0.5rem 1rem;
    position: relative;
    transition: color 0.3s ease;
  }

  /* Underline effect on hover */
  .navbar-nav .nav-link::after {
    content: "";
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: #0d6efd; /* Bootstrap primary color */
    transition: width 0.3s ease;
  }

  .navbar-nav .nav-link:hover::after,
  .navbar-nav .nav-link.active::after {
    width: 100%;
  }

  /* Active link style */
  .navbar-nav .nav-link.active {
    color: #0d6efd !important; /* Bootstrap primary */
    font-weight: 600;
  }

  /* Increase spacing between li */
  .navbar-nav .nav-item + .nav-item {
    margin-left: 1rem;
  }
</style>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 position-sticky top-0">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('images/logo_book.png') }}" alt="Logo" height="30" class="me-2">
      BookStore Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Books</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}" href="{{ route('authors.index') }}">Authors</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('publishers.*') ? 'active' : '' }}" href="{{ route('publishers.index') }}">Publishers</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('languages.*') ? 'active' : '' }}" href="{{ route('languages.index') }}">Languages</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">Orders</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}" href="{{ route('feedback.index') }}">Feedback</a></li>
      </ul>

    </div>
  </div>
</nav>


    <main class="container">
        <div class="content">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle CDN (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

