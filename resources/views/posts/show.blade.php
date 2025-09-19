@extends('layouts.app')

@section('title', 'Post Details')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <a href="{{ route('posts.list') }}" class="btn btn-sm btn-outline-secondary mb-3">← Back</a>
        <h1 id="post-title">Loading...</h1>
        <p class="text-muted" id="post-author"></p>
        <div id="post-body" class="mb-3"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    const postId = {{ $id }};
    $.ajax({
        url: `/api/posts/${postId}`,
        success: function(res) {
            const post = res.data;
            $('#post-title').text(post.title);
            $('#post-author').text('by ' + (post.author?.name ?? 'Unknown') + ' • ' + post.created_at);
            $('#post-body').text(post.body);
        },
        error: function() {
            $('#post-title').text('Error loading post');
        }
    });
});
</script>
@endsection
