@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Category Languages</div>

                    <div class="card-body">
                        <form action="{{ route('category_language.store', $category->id) }}" method="POST">
                            @csrf

                            <h3>Category: {{ $category->name }}</h3>

                            @foreach ($languages as $language)
                                <div class="form-group">
                                    <label for="name_{{ $language->id }}">Name ({{ $language->name }}):</label>
                                    <input type="text" class="form-control" id="name_{{ $language->id }}" name="names[{{ $language->id }}][name]" placeholder="Enter name for {{ $language->name }}">
                                    <input type="hidden" name="names[{{ $language->id }}][language_id]" value="{{ $language->id }}">
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Create Category Languages</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
sss