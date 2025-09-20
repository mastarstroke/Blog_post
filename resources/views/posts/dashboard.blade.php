@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>My Posts</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Post</button>
        </div>

        <div id="result"></div>

        {{-- Posts Table --}}
        <table class="table table-bordered" id="postsTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="addPostForm">
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
  </div>
</div>

{{-- Edit Modal (same as before) --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editPostForm">
          @csrf
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Body</label>
            <textarea name="body" id="edit_body" class="form-control" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    let token = '{{ session('jwt_token') }}';

    function loadPosts() {
        $.ajax({
            url: '/api/user/posts',
            method: 'GET',
            headers: { 'Authorization': 'Bearer {{ session('jwt') }}' },
            success: function(data) {
                let rows = '';
                data.data.forEach(function(post) {
                    rows += `
                        <tr>
                            <td>${post.title}</td>
                            <td>${post.body}</td>
                            <td>${post.created_at}</td>
                            <td>${post.updated_at}</td>
                            <td>
                                <button class="btn btn-sm btn-warning editBtn"
                                        data-id="${post.id}"
                                        data-title="${post.title}"
                                        data-body="${post.body}"
                                        title="Edit Post">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger deleteBtn"
                                        data-id="${post.id}"
                                        title="Delete Post">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>

                        </tr>`;
                });
                $('#postsTable tbody').html(rows);
            }
        });
    }

    loadPosts();

    // Add Post
    $('#addPostForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/posts',
            method: 'POST',
            headers: { 'Authorization': 'Bearer {{ session('jwt') }}' },
            data: $(this).serialize(),
            success: function() {
                $('#addModal').modal('hide');
                Swal.fire(
                    'Success!',
                    'Post Added successfully!',
                    'success'
                );
                $('#addPostForm')[0].reset();
                loadPosts();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors || {};
                let html = '<ul>';
                for (const field in errors) {
                    html += `<li>${errors[field][0]}</li>`;
                }
                html += '</ul>';
                $('#result').html('<div class="alert alert-danger">'+html+'</div>');
            }
        });
    });
    

    // Populate Edit Modal
    $(document).on('click', '.editBtn', function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_title').val($(this).data('title'));
        $('#edit_body').val($(this).data('body'));
        $('#editModal').modal('show');
    });

    // Update Post
    $('#editPostForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        $.ajax({
            url: '/api/posts/' + id,
            method: 'PUT',
           headers: { 'Authorization': 'Bearer {{ session('jwt') }}' },
            data: {
                title: $('#edit_title').val(),
                body: $('#edit_body').val()
            },
            success: function() {
                $('#editModal').modal('hide');
                Swal.fire(
                    'Success!',
                    'Post Updated successfully!',
                    'success'
                );
                loadPosts();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors || {};
                let html = '<ul>';
                for (const field in errors) {
                    html += `<li>${errors[field][0]}</li>`;
                }
                html += '</ul>';
                $('#result').html('<div class="alert alert-danger">'+html+'</div>');
            }
        });
    });

    // Delete Post
    $(document).on('click', '.deleteBtn', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the post.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/api/posts/' + id,
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer {{ session('jwt') }}' },
                    success: function() {
                        Swal.fire(
                            'Deleted!',
                            'Post deleted successfully!',
                            'success'
                        );
                        loadPosts();
                    },
                    error: function() {
                        Swal.fire(
                            'Error',
                            'Could not delete post',
                            'error'
                        );
                    }
                });
            }
        });
    });

});
</script>
@endsection
