@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <h1 class="mb-3">Register</h1>
        <div id="result"></div>
        <form id="registerForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/auth/register', 
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#result').html('<div class="alert alert-success">Registration successful! <a href="{{ route('login') }}">Login</a></div>');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors ?? {'error': ['Registration failed']};
                let html = '<ul>';
                for (const field in errors) {
                    html += `<li>${errors[field][0]}</li>`;
                }
                html += '</ul>';
                $('#result').html('<div class="alert alert-danger">'+html+'</div>');
            }
        });
    });
});
</script>
@endsection
