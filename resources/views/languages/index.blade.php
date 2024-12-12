@extends('layouts.portal')
@section('content')
    <style>
        /* General styles */
        .container {
            max-width: 1140px;
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
            text-overflow: ellipsis;
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Languages</h2>
                <a href="{{ route('languages.create') }}" class="btn btn-primary">Add Language</a>
                <table class="table">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Shortcode</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($languages as $language)
                        <tr>
                            <td>{{ $language->id }}</td>
                            <td>{{ $language->name }}</td>
                            <td>{{ $language->shortcode }}</td>
                            <td>{{ $language->status }}</td>
                            <td>
                                <a href="{{ route('languages.edit', [ 'language' => $language->id]) }}" class="btn btn-secondary">Edit</a>
                                <form action="{{ route('languages.destroy', ['language' => $language->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $languages->links() }}
            </div>
        </div>
    </div>
@endsection
