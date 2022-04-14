@extends('layouts.app')
@section('title', 'Posts')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>All posts</h1>
        </div>
        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                @can('create', App\Post::class)
                    <a href="{{ route('posts.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Create post</a>
                @endcan

                @can('create', App\Category::class)
                    <a href="{{ route('categories.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Create category</a>
                @endcan
            </div>
        </div>
    </div>

    @if (Session::has('category-deleted'))
        <div class="alert alert-success" role="alert">
            Category <strong>{{ Session::get('category-deleted') }}</strong> has been successfully deleted!
        </div>
    @endif

    @if (Session::has('post-deleted'))
        <div class="alert alert-success" role="alert">
            Post <strong>{{ Session::get('post-deleted') }}</strong> has been successfully deleted!
        </div>
    @endif


    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                @forelse ($posts as $post)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100 {{ $post->trashed() ? 'bg-danger text-white' : '' }}">
                            <img
                                src="{{
                                    asset(
                                        $post->cover_image_hashname
                                            ? 'storage/covers/' . $post->cover_image_hashname
                                            : 'images/default_post_cover.jpg'
                                    )
                                }}"
                                class="card-img-top"
                                alt="Post cover"
                            >
                            <div class="card-body">
                                <h5 class="card-title mb-0">{{ $post->title }}</h5>
                                <p class="small {{ $post->trashed() ? '' : 'text-secondary' }} mb-0">
                                    <span class="me-2">
                                        <i class="fas fa-user"></i>
                                        <span>
                                            {{
                                                $post->author
                                                    ? 'By ' . $post->author->name
                                                    : 'No author'
                                            }}
                                        </span>
                                    </span>

                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                                    </span>
                                </p>

                                @foreach ($post->categories as $category)
                                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                                        <span class="badge bg-{{ $category->style }}">{{ $category->name }}</span>
                                    </a>
                                @endforeach

                                <p class="card-text mt-1">
                                    {{
                                        $post->description
                                            ? $post->description
                                            : 'No short description'
                                    }}
                                </p>
                            </div>
                            <div class="card-footer">
                                @if ($post->trashed())
                                    @can('restore', $post)
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#restore-confirm-modal-{{ $loop->iteration }}">
                                            <i class="fas fa-recycle"></i> <span>Restore post</span>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="restore-confirm-modal-{{ $loop->iteration }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-black">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Confirm restore</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to restore post <strong>{{ $post->title }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-primary"
                                                            onclick="document.getElementById('restore-post-form-{{ $loop->iteration }}').submit();"
                                                        >
                                                            Yes, restore this post
                                                        </button>

                                                        <form id="restore-post-form-{{ $loop->iteration }}" action="{{ route('posts.restore', $post) }}" method="POST" class="d-none">
                                                            @method('PATCH')
                                                            @csrf
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-outline-light" disabled>
                                            <span><i class="fas fa-times"></i> <span>Deleted post</span></span>
                                        </button>
                                    @endcan
                                @else
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-primary">
                                        <span>View post</span> <i class="fas fa-angle-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            No posts found!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>

        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Categories
                        </div>
                        <div class="card-body">
                            @foreach ($categories as $category)
                                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                                    <span class="badge bg-{{ $category->style }}">{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Statistics
                        </div>
                        <div class="card-body">
                            <div class="small">
                                <ul class="fa-ul">
                                    {{-- TODO: Read stats from DB --}}
                                    <li><span class="fa-li"><i class="fas fa-user"></i></span>Users: {{ $users_count }}</li>
                                    <li><span class="fa-li"><i class="fas fa-layer-group"></i></span>Categories: {{ $categories->count() }}</li>
                                    <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Posts: {{ $posts->total() }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
