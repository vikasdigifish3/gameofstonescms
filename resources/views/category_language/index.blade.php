@extends('layouts.portal')

@section('content')
    <div class="container">
        <h1>Category Languages</h1>
        <a href="{{ route('category_language.create') }}" class="btn btn-primary">Create Category Language</a>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Language</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryLanguages as $categoryLanguage)
                    <tr>
                        <td>{{ $categoryLanguage->id }}</td>
                        <td>{{ $categoryLanguage->category->name }}</td>
                        <td>{{ $categoryLanguage->language->name }}</td>
                        <td>{{ $categoryLanguage->name }}</td>
                        <td>
                            <a href="{{ route('category_language.edit', $categoryLanguage) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('category_language.destroy', $categoryLanguage) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category language?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
