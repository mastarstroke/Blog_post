@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h1 class="mb-3">All Posts</h1>
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search posts...">
        </div>
        <ul class="list-group" id="posts-list">
            <li class="list-group-item">Loading posts...</li>
        </ul>
        <nav class="mt-3">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    function loadPosts(page = 1, search = '') {
        $('#posts-list').html('<li class="list-group-item">Loading posts...</li>');
        $.ajax({
            url: '/api/posts?page=' + page + '&search=' + encodeURIComponent(search),
            success: function(response) {
                $('#posts-list').empty();
                response.data.forEach(function(post) {
                    $('#posts-list').append(`
                        <li class="list-group-item">
                            <h5><a href="/posts/${post.id}">${post.title}</a></h5>
                            <small class="text-muted">by ${post.author?.name ?? 'Unknown'} • ${post.created_at}</small>
                        </li>
                    `);
                });

                // pagination
                let meta = response.meta;
                let pagination = '';
                for (let i = 1; i <= meta.last_page; i++) {
                    pagination += `<li class="page-item ${i==meta.current_page?'active':''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                $('#pagination').html(pagination);
            },
            error: function() {
                $('#posts-list').html('<li class="list-group-item text-danger">Error loading posts</li>');
            }
        });
    }

    loadPosts();

    $('#search').on('keyup', function() {
        loadPosts(1, $(this).val());
    });

    $('#pagination').on('click', 'a', function(e) {
        e.preventDefault();
        loadPosts($(this).data('page'), $('#search').val());
    });
});
</script>
@endsection
