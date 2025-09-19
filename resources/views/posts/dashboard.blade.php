@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <h1 class="mb-3">Add New Post</h1>
        <div id="result"></div>
        <form id="postForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Body</label>
                <textarea name="body" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    $('#postForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/posts',
            method: 'POST',
            headers: {
                'Authorization': 'Bearer {{ session('jwt') }}' // put your JWT in session after login
            },
            data: $(this).serialize(),
            success: function() {
                $('#result').html('<div class="alert alert-success">Post created successfully!</div>');
                $('#postForm')[0].reset();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
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
