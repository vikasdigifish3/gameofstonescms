@extends('layouts.portal')

@section('content')
    <h2>Edit Category Language</h2>

    <form action="{{ route('category_language.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h3>Category: {{ $category->name }}</h3>

        @foreach ($languages as $language)
            <div class="form-group">
                <label for="name_{{ $language->id }}">Name ({{ $language->name }}):</label>
                <input type="text" class="form-control" id="name_{{ $language->id }}" name="names[{{ $language->id }}]" value="{{ $categoryLanguages->where('language_id', $language->id)->first()->name ?? '' }}" placeholder="Name for {{ $language->name }}">
            </div>

           
        @endforeach

        <button type="submit" class="btn btn-primary">Update Category Language</button>
    </form>
@endsection
