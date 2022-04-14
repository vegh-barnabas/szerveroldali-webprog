@extends('layouts.app')
{{-- TODO: Post title --}}
@section('title', 'View post: ')

@section('content')
<div class="container">

    @if (Session::has('post-created'))
        <div class="alert alert-success" role="alert">
            Post <strong>{{ Session::get('post-created') }}</strong> has been successfully created!
        </div>
    @endif

    @if (Session::has('post-updated'))
        <div class="alert alert-success" role="alert">
            Post <strong>{{ Session::get('post-updated') }}</strong> has been successfully updated!
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>{{ $post->title }}</h1>

            <p class="small text-secondary mb-0">
                <i class="fas fa-user"></i>
                <span>
                    {{
                        $post->author
                            ? 'By ' . $post->author->name
                            : 'No author'
                    }}
                </span>
            </p>
            <p class="small text-secondary mb-0">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $post->created_at->format('d/m/Y') }}</span>
            </p>

            <div class="mb-2">
                @foreach ($post->categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <span class="badge bg-{{ $category->style }}">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('posts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>

        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                @can('update', $post)
                    <a role="button" class="btn btn-sm btn-primary" href="{{ route('posts.edit', $post) }}"><i class="far fa-edit"></i> Edit post</a>
                @endcan

                @can('delete', $post)
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt"></i> Delete post</button>
                @endcan
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete post <strong>{{ $post->title }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-post-form').submit();"
                    >
                        Yes, delete this post
                    </button>

                    <form id="delete-post-form" action="{{ route('posts.destroy', $post) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <img
        id="cover_preview_image"
        src="{{ asset($default_cover) }}"
        alt="Cover preview"
        class="my-3"
    >

    <div class="mt-3">
        {!! nl2br(e($post->text)) !!}
    </div>
</div>
@endsection
