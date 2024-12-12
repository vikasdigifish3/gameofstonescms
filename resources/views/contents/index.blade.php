@extends('layouts.portal')

@section('content')
    <style>
        /* General styles */
        .container {

            margin: 0 auto;
            padding: 1rem;
        }

        h2 {
            margin-bottom: 1.5rem;
        }

        /* Table styles */

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        /* Button styles */
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            user-select: none;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
        }

        .btn-secondary {
            color: #fff;
            background-color: #198754;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            color: #fff;
            background-color: #198730;
        }

        .btn-secondary:focus {
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            color: #fff;
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-danger:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
        }

        form.d-inline {
            display: inline-block;
        }

        h2 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: black;
            font-weight: bold;
        }

        .table td {
            text-align: center;
        }

        .table td {
            max-width: 160px;
            overflow: hidden;
            /*text-overflow: ellipsis;*/
            white-space: nowrap;
        }

        /* Pagination styles */
        .pagination .page-item .page-link {
            font-size: 1rem; /* Adjust the font size to your preference */
        }

        .pagination .page-item .page-link:focus,
        .pagination .page-item .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            box-shadow: none;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }


    </style>
    <style>
        .language-content {
            position: relative;
            display: inline-block;
        }

        .language-content .hover-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1;
        }

        .language-content:hover .hover-content {
            display: block;
        }

        .hover-container {
            position: relative;
            display: inline-block;
        }

        .hover-container .hover-content {
            display: none;
            position: fixed;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 10;
        }

        .hover-container:hover .hover-content {
            display: block;
        }
    </style>
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h2>Contents </h2>
                <a href="{{ route('contents.create',) }}" class="btn btn-primary">Add New Content</a>
                {{--      Search form--}}
                <form action="{{ route('contents.index') }}" method="get" class="d-inline-block float-right">
                    <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                    <input type="hidden" name="sort_order" value="{{ $sort_order }}">

                    <div class="input-wrapper" style="display: flex; justify-content: space-between;">
                        <div class="input-group">
                            <select class="form-control" id="portal" name="portal">
                                <option value="0" {{$portal_id == 0 ? 'selected="selected"' : ' '}}>--Select Portal--
                                </option>
                                @foreach($portals as $portal)
                                    <option
                                        value="{{ $portal->id }}" {{$portal_id == $portal->id ? 'selected="selected"' : ' '}}>{{ $portal->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="search" class="form-control" value="{{ $searchQuery }}"
                                   placeholder="Search content">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>

                        <div class="input-group">
                            <select class="form-control" id="content_type" name="content_type">
                                <option value="" {{$selected_content_type == "" ? 'selected="selected"' : ''}}>--Select
                                    Content Type--
                                </option>
                                @foreach($content_types as $content_type)
                                    <option
                                        value="{{ $content_type }}" {{$selected_content_type == $content_type ? 'selected="selected"' : ''}}>{{ ucfirst($content_type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <div class="list-group">
                                <a href="{{ route('tags.create') }}"
                                   class="list-group-item list-group-item-action">Tags</a>
                            </div>
                        </div>
                    </div>
                </form>

                <a href="{{ $sortableHeaders['download'] }}" target="blank">Download</a>

                <table class="table table-bordered table-sm">
                    <thead>
                    <thead>
                    <tr>
                        <th><a href="{{ $sortableHeaders['id'] }}">ID</a></th>
                        <th><a href="{{ $sortableHeaders['name'] }}">Name</a></th>
                        <th>Short Name</th>
                        <th><a href="{{ $sortableHeaders['category_id'] }}">Category</a></th>
                        <th>Thumb Image</th>
                        <th>Banner Image</th>
                        <th>File Url</th>
                        <th>Content Type</th>
                        <th>Description</th>
                        <th>Languages Present</th>
                        <th><a href="{{ $sortableHeaders['language_id'] }}">Language</a></th>
                        <th><a href="{{ $sortableHeaders['status'] }}">Status</a></th>
                        <th>Countries</th>
                        <th>Portals</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    </thead>
                    <tbody>
                    @foreach ($contents as $content)
                        <tr>
                            <td>{{ $content->id }}</td>
                            <td>
                                <div class="hover-container">
                                    {{ $content->name }}
                                    <div class="hover-content">
                                        <strong> {{ $content->name }}</strong><br>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $content->short_title }}</td>
                            <td>{{ $content->category->name }}</td>
                            <td><img src="{{ $content->thumb_url }}" width="50" height="50"></td>

                            <td>
                                @if (!empty($content->banner_url))
                                    <img src="{{ $content->banner_url }}" width="100" height="50">
                                @else
                                    <span>No image available</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ empty($content->file_path) ? $content->remote_file_path :  $content->file_path}}" target="_blank">View File</a></td>
                            <td>{{ $content->content_type }}</td>
                            <td>{{ $content->description }}</td>
                            <td>
                                @foreach($content->translations as $translation)
                                    <div class="language-content" style="text-transform: uppercase">
                                        {{ $translation->language->shortcode }},
                                        <div class="hover-content">
                                            <strong style="text-transform: uppercase"> {{ $translation->language->shortcode }}</strong><br>
                                        </div>
                                    </div>
                                @endforeach
                            </td>

                            <td>
                                <a href="{{ route('languageContents.edit', [$content->id, 'return' => url()->full()]) }}"
                                   class="btn btn-primary">Translations</a></td>
                            <td>{{ $content->status ? 'ACTIVE':'INACTIVE' }}</td>
                            <td><a href="{{ route('contents.countries.edit', ['content' => $content->id]) }}"
                                   class="btn btn-primary">Countries</a></td>
                            <td><a href="{{ route('contents.editPortals', ['id' => $content->id]) }}"
                                   class="btn btn-primary">Portals</a></td>
                            <td>
                                <a href="{{ route('contents.edit', ['content' => $content->id,'return'=> url()->full()]) }}"
                                   class="btn btn-primary">Edit</a>

                                <form action="{{ route('contents.destroy',  ['content' => $content->id]) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $contents->links('vendor.pagination.bootstrap-4') }}

            </div>
        </div>
    </div>

@endsection
