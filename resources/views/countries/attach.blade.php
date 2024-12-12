@extends('layouts.portal')

@section('content')<!-- countries/attach.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-bottom: 20px;
        }

        .form-container {
            width: 400px;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .form-container button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Block Contents from {{ $country->name }}</h1>

    <div class="form-container">
        <form action="{{ route('countries.contents.store', $country->id) }}" method="POST">
            @csrf
            <div>
                <label for="content_id">Content:</label>
                <select name="content_id" id="content_id">
                    @foreach ($contents as $content)
                        <option value="{{ $content->id }}">{{ $content->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Block</button>
        </form>
    </div>
</body>
</html>
@endsection
