@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <h1 class="mb-3">Login</h1>
        <div id="result"></div>
        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="mt-3">No account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/auth/login',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                let token = res.access_token ?? res.token ?? null;
                if (token) {
                    $.post('/save-token', {
                        _token: '{{ csrf_token() }}',
                        token: token
                    }).done(function() {
                        $('#result').html('<div class="alert alert-success">Logged in!</div>');
                        window.location.href = "{{ route('posts.dashboard') }}";
                    });
                } else {
                    $('#result').html('<div class="alert alert-danger">No token returned</div>');
                }
            },
            error: function(xhr) {
                $('#result').html('<div class="alert alert-danger">Invalid credentials</div>');
            }
        });
    });
});
</script>
@endsection
