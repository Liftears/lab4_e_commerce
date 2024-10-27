<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eternal Nap Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-body-secondary">
    <div class="container-fluid">
        <div class="d-flex justify-content-center mx-auto align-items-center">
            <a class="navbar-brand" href="{{ url('/products') }}">
                <img src="{{ asset('storage/images/Eternal_Nap_Letter-Bg.png') }}" alt="EternalNap Logo" height="40" style="vertical-align: middle;">
            </a>
            <a href="{{ route('helloworl') }}" style="color: inherit; vertical-align: middle; margin-left: -15px; margin-bottom: -5.5px">.</a>
        </div>

        <div class="d-flex justify-content-end">

        <div class="ms-auto">
            @auth
            <div class="dropdown">
    <a href="#" class="text-white dropdown-toggle d-flex align-items-center no-arrow" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ Auth::user()->name }}
    </a>
    <div class="dropdown-menu dropdown-menu-right bg-dark text-white" aria-labelledby="userDropdown">
        <a class="dropdown-item text-white" href="{{ route('profile.edit') }}">My Account</a>
        <a class="dropdown-item text-white" href="{{ route('products.index') }}">Product</a>
        <a class="dropdown-item text-white" href="{{ route('categories.index') }}">Category</a>
        <a class="dropdown-item text-white" href="{{ route('users.index') }}">Users</a>
        <a class="dropdown-item text-white" href="{{ route('admin.orders.index') }}">Orders</a>
        <div class="dropdown-divider bg-light"></div>
        <a class="dropdown-item text-white" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

</form>
            @else
            <a href="{{ route('register') }}" style="color: inherit">Sign Up</a>&nbsp;|&nbsp;<a href="{{ route('login') }}" style="color: inherit">Login</a>
            @endauth
        </div>
        </div>
    </div>
</nav>


    <div class="container mt-4">
        @yield('content')
    </div>

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
