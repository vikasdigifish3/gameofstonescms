@extends('layouts.portal')


@section('content')
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

            <div class="row">
                <div class="col-md-12">
                    <h2>Create Content</h2>
                    <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option>Select Category</option>
                                @foreach($categories as $category)
                                    @if($category->parent_category == 0 )
                                        <!-- Check if the category is a parent category and if it belongs to the specified portal -->
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($categories as $subcategory)
                                                @if($subcategory->parent_category == $category->id )
                                                    <option
                                                        value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                         <div class="form-group">
                            <label for="short_title">Short Title</label>
                            <input type="text" class="form-control" id="short_title" name="short_title">
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="form-select" id="tags" name="tags[]" multiple>
                                @foreach(\App\Models\Tag::all() as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control"  name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="0">Inactive</option>
                                <option value="1" selected>Active</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="content_type">Content Type</label>
                            <select class="form-control" id="content_type" name="content_type" required>
                                <option value="video">Video</option>
                                <option value="infographic">Infographic</option>
                                <option value="article">Article</option>
                            </select>
                            @error('content_type')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <input type="hidden" name="language_id" value="1">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                   value="1">
                            <label class="form-check-label" for="is_featured">Featured</label>
                        </div>
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail Image</label>
                            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail">
                            @error('thumbnail')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="banner_file">Banner Image</label>
                            <input type="file" class="form-control-file" id="banner_file" name="banner">
                            @error('banner')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="file_upload">File Upload</label>
                            <input type="file" class="form-control-file" id="file_upload" name="file_path">
                            @error('file_path')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Add this new div for the URL input -->
                        <div class="form-group">
                            <label for="remote_file_path">File URL</label>
                            <input type="url" class="form-control" id="remote_file_path" name="remote_file_path"
                                   placeholder="Enter URL">
                            @error('remote_file_path')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Content</button>
                    </form>
                </div>
            </div>
        </div>
    <script>
        $(document).ready(function() {
            $('#tags').select2({
                placeholder: "Select tags",
                allowClear: true
            });
        });
    </script>
    @endsection
