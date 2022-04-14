@extends('layouts.app')
@section('title', 'Edit category: ' . $category->name)

@section('content')
<div class="container">
    <h1>Edit category</h1>
    <div class="mb-4">
        <a href="{{ route('posts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    @if (Session::has('category-updated'))
        <div class="alert alert-success" role="alert">
            Category <strong>{{ Session::get('category-updated') }}</strong> has been successfully updated!
        </div>
    @endif

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @method('PATCH')
        @csrf

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="style" class="col-sm-2 col-form-label py-0">Style*</label>
            <div class="col-sm-10">
                @foreach ($styles as $style)
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="style"
                            id="{{ $style }}"
                            value="{{ $style }}"
                            {{
                                old('style') === $style
                                    ? 'checked'
                                    : (
                                        $category->style === $style && !old('style')
                                            ? 'checked'
                                            : ''
                                    )
                            }}
                        >
                        <label class="form-check-label" for="{{ $style }}">
                            <span class="badge bg-{{ $style }}">{{ $style }}</span>
                        </label>
                    </div>
                @endforeach
                @error('style')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
        </div>

    </form>
</div>
@endsection
