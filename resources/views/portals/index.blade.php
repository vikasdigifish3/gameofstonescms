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
            font-size: 2rem;
            color: black;
            font-weight: bold;
        }

        /* Table styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

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

        .table td {
            text-align: left;
        }

    </style>

    <!-- resources/views/portals/index.blade.php -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               
                 <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h2>Portals</h2>
            <button class="btn btn-primary">Add Portals</button>
        </div>
                <a href="{{ route('portals.create') }}" class="btn btn-primary">Add Portal</a>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Categories</th>
                        <th>Contents</th>
                        <th>&nbsp;Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($portals as $portal)
                        <tr>
                            <td>{{ $portal->id }}</td>
                            <td>{{ $portal->name }}</td>
                            <td>{{ $portal->status ? 'ACTIVE': 'INACTIVE' }}</td>
                            <td><a href="{{ route('portals.editCategories', $portal->id) }}" class="btn btn-secondary">Modify Categories</a></td>
                            <td><a href="{{ route('portals.editContents', $portal->id) }}" class="btn btn-secondary">Modify Contents</a></td>
                            <td>
                                <a href="{{ route('portals.edit', $portal->id) }}" class="btn btn-secondary">Edit</a>
                                </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $portals->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
