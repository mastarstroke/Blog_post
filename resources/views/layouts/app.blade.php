<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Blog')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('head')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('posts.list') }}">My Blog</a>
        <div>
            @if(session('jwt'))
                <a class="btn btn-outline-light btn-sm" href="{{ route('posts.dashboard') }}">Dashboard</a>

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            @else
                <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a>
            @endif
        </div>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

@yield('scripts')
</body>
</html>
