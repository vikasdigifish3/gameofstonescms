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
    <div class="row">
        <div class="col-md-12">
            <h2>Edit Content</h2>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contents.update', [ 'content' => $content->id]) }}"
                  method="post" enctype="multipart/form-data">

                @csrf


                @method('PUT')
                <input type="hidden" name="return" value="{{ request('return') }}">

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option>Select Category</option>
                        @foreach($categories as $category)
                            @if($category->parent_category == 0 )
                                <optgroup label="{{ $category->name }}">
                                    @foreach($categories as $subcategory)
                                        @if($subcategory->parent_category == $category->id)
                                            <option value="{{ $subcategory->id }}" {{ $content->category_id == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $content->name }}" required>
                </div>

                <div class="form-group">
                    <label for="short_title">Short Title</label>
                    <input type="text" class="form-control" id="short_title" name="short_title"  value="{{ $content->short_title }}">
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <select id="tags" name="tags[]" class="form-select" multiple>
                        @foreach(\App\Models\Tag::all() as $tag)
                            <option value="{{ $tag->id }}" @if(isset($content) && $content->tags->contains($tag->id)) selected @endif>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control"  name="description">{{ $content->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-select" id="status" name="status" aria-label="Default select example" required>
                        <option value="">Select the status</option>
                        <option value="0" {{ $content->status == 0 ? 'selected' : '' }}>Inactive</option>
                        <option value="1" {{ $content->status == 1 ? 'selected' : '' }}>Active</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="content_type">Content Type</label>
                    <select class="form-control" id="content_type" name="content_type" required>
                        <option value="video" {{ $content->content_type == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="infographic" {{ $content->content_type == 'infographic' ? 'selected' : '' }}>Infographic</option>
                        <option value="article" {{ $content->content_type == 'article' ? 'selected' : '' }}>Article</option>
                    </select>
                    @error('content_type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="language_id" value="1">

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                           value="1" {{ $content->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">Featured</label>
                </div>

                <div class="form-group">
                    <label for="thumb_file">Thumbnail Image</label>
                    <input type="file" class="form-control-file" id="thumb_file" name="thumbnail"
                           value="{{ $content->thumb_url }}">
                    @error('thumbnail')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>

                <div class="form-group">
                    <label for="banner_file">Banner Image</label>
                    <input type="file" class="form-control-file" id="banner_file" name="banner"
                           value="{{ $content->thumb_url }}">
                    @error('banner')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="file_upload">File Upload</label>
                    <input type="file" class="form-control-file" id="file_upload" name="file_path"
                           value="{{ $content->file_path }}">
                    @error('file_path')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="remote_file_path">File URL</label>
                    <input type="text" class="form-control" id="remote_file_path" name="remote_file_path" placeholder="Enter URL"
                           value="{{ empty($content->remote_file_path) ?$content->file_path: $content->remote_file_path }}">
                    @error('remote_file_path')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Content</button>
                <a href="{{ route('contents.index') }}" class="btn btn-secondary"
                   style="color: #fff; background-color: #0d6efd; border-color: #0d6efd;">Go Back</a>
                <div class="container">
                    <div class="container">
            </form>
        </div>
    </div>
    </div>

@endsection
