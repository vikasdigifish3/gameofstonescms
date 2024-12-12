@extends('layouts.portal')

@section('content')
    <div class="container">
        <h2>Tags</h2>
        <a href="{{ route('tags.create') }}" class="btn btn-primary">Add New Tag</a>
        <table class="table mt-3">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td>{{ $tag->name }}</td>
                    <td>
                        <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
