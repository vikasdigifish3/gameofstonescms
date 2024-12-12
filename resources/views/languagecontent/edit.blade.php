@extends('layouts.portal')

<style>
    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    label {
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-control-file {
        font-size: 16px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
    }

    .form-control-file:hover {
        cursor: pointer;
    }

    .text-danger {
        color: #f44336;
        font-size: 14px;
        margin-top: 5px;
    }
</style>

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12"> <!-- Adjusted for full width -->
                <div class="card">
                    <div class="card-header" style="color: green;">Edit "{{ $content->name }}"</div>
                    <div class="card-body">
                        <form action="{{ route('languageContents.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @foreach ($languages as $language)
                                @php
                                    $languageData = $alldata[$language->id] ?? [];
                                @endphp
                                <h3 style="color: blue;">{{ $language->name }}</h3>
                                <input type="hidden" name="languages[]" value="{{ $language->id }}">
                                <div class="row" style="flex-wrap: nowrap">
                                    <div class="col-md-3 form-group">
                                        <label for="name_{{ $language->id }}">Name:</label>
                                        <input type="text" class="form-control" id="name_{{ $language->id }}" name="names[]" value="{{ $languageData['name'] ?? '' }}" placeholder="Name for {{ $language->name }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="short_title_{{ $language->id }}">Short Title:</label>
                                        <input type="text" class="form-control" id="short_title_{{ $language->id }}" name="short_titles[{{ $language->id }}]" value="{{ $languageData['short_title'] ?? '' }}" placeholder="Short Title for {{ $language->name }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="description_{{ $language->id }}">Description:</label>
                                        <textarea class="form-control" id="description_{{ $language->id }}" name="descriptions[]" rows="3" placeholder="Description for {{ $language->name }}">{{ $languageData['description'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="thumb_{{ $language->id }}">Thumbnail Image:</label>
                                        <input type="file" class="form-control-file" id="thumb_{{ $language->id }}" name="thumbs[]">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="banner_{{ $language->id }}">Banner Image:</label>
                                        <input type="file" class="form-control-file" id="banner_{{ $language->id }}" name="banners[]">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="file_{{ $language->id }}">File:</label>
                                        <input type="file" class="form-control-file" id="file_{{ $language->id }}" name="files[]">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="remote_file_path_{{ $language->id }}">File URL:</label>
                                        <input type="url" class="form-control" id="remote_file_path_{{ $language->id }}" name="remote_file_path[]" placeholder="Enter URL" value="{{ $languageData['remote_file_path'] ?? '' }}">
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            <button type="submit" class="btn btn-primary">Update Language Contents</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>@endsection
