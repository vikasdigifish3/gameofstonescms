@extends('layouts.portal')

@section('content')<!-- continents/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Continents</h1>

    <a href="{{ route('continents.create') }}">Add Continent</a>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($continents as $continent)
                <tr>
                    <td>{{ $continent->name }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('continents.edit', $continent->id) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('continents.destroy', $continent->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

@endsection