@extends('layouts.app')
@section('title', 'Edit category')

@section('content')
<div class="container">
    <h1>Edit category</h1>
    <div class="mb-4">
        {{-- Link --}}
        <a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- Session flashes --}}
    @if (Session::has('category-updated'))
        <div class="alert alert-success" role="alert">
            Category <strong class="badge bg-{{ Session::get('category-updated')[0] }}">{{ Session::get('category-updated')[1] }}</strong> successfully edited to <strong class="badge bg-{{ Session::get('category-updated')[2] }}">{{ Session::get('category-updated')[3] }}</strong> !
        </div>
    @endif

    {{-- action, method --}}
    <form action="{{ route('categories.update', $category) }}" method="post">
        @method('patch')
        @csrf
        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ? old('name') : $category->name }}">
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
                            {{-- checked --}}
                            {{ old('style') === $style ? 'checked' : ($category->style === $style ? 'checked' : '') }}
                        >
                        <label class="form-check-label" for="{{ $style }}">
                            <span class="badge bg-{{ $style }}">{{ $style }}</span>
                        </label>
                    </div>
                @endforeach
                {{-- TODO: Error handling --}}
                @error('style')
                    <div class="text-danger small">
                        {{ $message }}
                    </div>
                @enderror

            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Edit</button>
        </div>

    </form>
</div>
@endsection
