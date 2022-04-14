@extends('layouts.app')
@section('title', 'Edit post: ' . $post->title)

@section('content')
<div class="container">
    <h1>Edit post: {{ $post->title }}</h1>
    <div class="mb-4">
        <a href="{{ route('posts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- IMPORTANT: enctype --}}
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <div class="form-group row mb-3">
            <label for="title" class="col-sm-2 col-form-label">Title*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $post->description) }}">
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="text" class="col-sm-2 col-form-label">Text*</label>
            <div class="col-sm-10">
                <textarea rows="5" class="form-control @error('text') is-invalid @enderror" id="text" name="text">{{ old('text', $post->text) }}</textarea>
                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="categories" class="col-sm-2 col-form-label py-0">Categories</label>
            <div class="col-sm-10">
                <div class="row">
                    @php
                        $checked_categories = old('categories', $post->categories->pluck('id')->toArray());
                    @endphp

                    @forelse ($categories->chunk(5) as $categoryChuck)
                        <div class="col-6 col-md-3 col-lg-2">
                            @foreach ($categoryChuck as $category)
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        value="{{ $category->id }}"
                                        id="category{{ $loop->iteration }}"
                                        name="categories[]"
                                        @if (
                                            is_array($checked_categories) &&
                                            in_array($category->id, $checked_categories)
                                        )
                                            checked
                                        @endif
                                    >
                                    <label for="category{{ $loop->iteration }}" class="form-check-label">
                                        <span class="badge bg-{{ $category->style }}">{{ $category->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>No categories found</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label">Settings</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="remove_cover_image" name="remove_cover_image" {{ old('remove_cover_image') ? 'checked' : '' }}>
                        <label for="remove_cover_image" class="form-check-label">Remove cover image</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row mb-3" id="cover_image_section">
            <label for="cover_image" class="col-sm-2 col-form-label">Cover image</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image">
                            @error('cover_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div id="cover_preview" class="col-12">
                            <p>Cover preview:</p>
                            <img
                                id="cover_preview_image"
                                src="{{ asset($default_cover) }}"
                                alt="Cover preview"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const removeCoverInput = document.querySelector('input#remove_cover_image');
    const coverImageSection = document.querySelector('#cover_image_section');
    const coverImageInput = document.querySelector('input#cover_image');
    const coverPreviewContainer = document.querySelector('#cover_preview');
    const coverPreviewImage = document.querySelector('img#cover_preview_image');
    // Render Blade to JS code:
    const defaultCover = `{{ asset($default_cover) }}`;

    removeCoverInput.onchange = event => {
        if (removeCoverInput.checked) {
            coverImageSection.classList.add('d-none');
        } else {
            coverImageSection.classList.remove('d-none');
        }
    }

    coverImageInput.onchange = event => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewImage.src = URL.createObjectURL(file);
        } else {
            coverPreviewImage.src = defaultCover;
        }
    }
</script>
@endsection
